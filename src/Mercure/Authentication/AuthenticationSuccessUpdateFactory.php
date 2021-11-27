<?php

declare(strict_types=1);

namespace App\Mercure\Authentication;

use App\Entity\User\User;
use App\Enum\Authentication\Channel;
use App\Mercure\Authentication\Contract\AbstractUpdateFactory;
use App\Messenger\Message;
use Symfony\Component\Mercure\Update;

class AuthenticationSuccessUpdateFactory extends AbstractUpdateFactory
{
    public function create(
        Message $message,
        User $user,
        string $token
    ): Update {
        $userId = $user
            ->getId()
            ->toRfc4122();

        return new Update(
            $this->getTopic(Channel::USER_AUTHENTICATED),
            json_encode([
                'channel' => Channel::USER_AUTHENTICATED,
                'originatedMessageId' => $this->getOriginatedMessageId($message),
                'id' => $userId,
                'token' => $token
            ])
        );
    }
}