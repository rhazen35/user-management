<?php

declare(strict_types=1);

namespace App\Mercure\User;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Enum\User\Properties;
use App\Mercure\Authentication\Contract\AbstractUpdateFactory;
use App\Messenger\Message;
use Symfony\Component\Mercure\Update;

class UserDeletedUpdateFactory extends AbstractUpdateFactory
{
    public function create(
        Message $message,
        User $user
    ): Update {
        $userId = $user
            ->getId()
            ->toRfc4122();

        return new Update(
            $this->getTopic(Channel::USER_DELETED),
            json_encode([
                'originatedMessageId' => $this->getOriginatedMessageId($message),
                Properties::ID => $userId
            ])
        );
    }
}