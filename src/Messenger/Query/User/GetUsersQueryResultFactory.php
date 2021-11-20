<?php

declare(strict_types=1);

namespace App\Messenger\Query\User;

use App\Enum\User\Channel;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\Message;
use App\Messenger\Query\QueryResult;
use App\View\Pagination\PaginationView;
use Symfony\Component\Messenger\Envelope;

class GetUsersQueryResultFactory extends AbstractMessageFactory
{
    public function create(
        Message $message,
        PaginationView $paginationView
    ): Envelope {
        $channel = Channel::GET_USERS_RESULT;
        $idStamp = $this->getIdStamp();

        $queryResult = new QueryResult(
            $channel,
            $paginationView,
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