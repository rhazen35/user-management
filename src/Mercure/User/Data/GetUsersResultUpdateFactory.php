<?php

declare(strict_types=1);

namespace App\Mercure\User\Data;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Mercure\Authentication\Contract\AbstractUpdateFactory;
use App\Messenger\Message;
use Symfony\Component\Mercure\Update;

class GetUsersResultUpdateFactory extends AbstractUpdateFactory
{
    /**
     * @param array<User> $users
     */
    public function create(
        Message $message,
        array $users
    ): Update {
        return new Update(
            $this->getTopic(Channel::GET_USERS_RESULT),
            json_encode([
                'originatedMessageId' => $this->getOriginatedMessageId($message),
                'users' => $users
            ])
        );
    }
}