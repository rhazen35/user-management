<?php

declare(strict_types=1);

namespace App\Messenger\Query\User;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Enum\User\Properties;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\Message;
use App\Messenger\Query\QueryResult;
use Symfony\Component\Messenger\Envelope;

class GetUserQueryResultFactory extends AbstractMessageFactory
{
    public function create(
        Message $message,
        User $user
    ): Envelope {
        $channel = Channel::GET_USER_RESULT;
        $idStamp = $this->getIdStamp();

        $queryResult = new QueryResult(
            $channel,
            [Properties::EMAIL => $user->getEmail()],
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