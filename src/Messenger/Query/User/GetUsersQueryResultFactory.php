<?php

declare(strict_types=1);

namespace App\Messenger\Query\User;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\Message;
use App\Messenger\Query\QueryResult;
use Symfony\Component\Messenger\Envelope;

class GetUsersQueryResultFactory extends AbstractMessageFactory
{
    /**
     * @param array<User> $users
     */
    public function create(
        Message $message,
        array $users
    ): Envelope {
        $channel = Channel::GET_USERS_RESULT;
        $idStamp = $this->getIdStamp();

        $queryResult = new QueryResult(
            $channel,
            $users,
            $idStamp->getId(),
            $this->getOriginatedMessageId($message)
        );

        return new Envelope(
            $queryResult,
            [
                $this->getAmqpStamp($channel),
                $idStamp
            ]
        );
    }
}