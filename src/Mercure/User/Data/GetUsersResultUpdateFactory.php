<?php

declare(strict_types=1);

namespace App\Mercure\User\Data;

use App\Enum\User\Channel;
use App\Mercure\Authentication\Contract\AbstractUpdateFactory;
use App\Messenger\Message;
use App\View\Pagination\PaginationView;
use Symfony\Component\Mercure\Update;

class GetUsersResultUpdateFactory extends AbstractUpdateFactory
{
    public function create(
        Message $message,
        PaginationView $paginationView
    ): Update {
        return new Update(
            $this->getTopic(Channel::GET_USERS_RESULT),
            json_encode([
                'channel' => Channel::GET_USERS_RESULT,
                'originatedMessageId' => $this->getOriginatedMessageId($message),
                'users' => $paginationView
            ])
        );
    }
}