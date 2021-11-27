<?php

declare(strict_types=1);

namespace App\Mercure\User;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Mercure\Authentication\Contract\AbstractUpdateFactory;
use App\Messenger\Message;
use Symfony\Component\Mercure\Update;

class UserCreatedUpdateFactory extends AbstractUpdateFactory
{
    public function create(
        Message $message,
        User $user
    ): Update {
        $userId = $user
            ->getId()
            ->toRfc4122();

        return new Update(
            $this->getTopic(Channel::USER_CREATED),
            json_encode([
                'channel' => Channel::USER_CREATED,
                'originatedMessageId' => $this->getOriginatedMessageId($message),
                'id' => $userId
            ])
        );
    }
}