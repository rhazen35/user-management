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

        $topic = $this->mercurePublicUrl.DIRECTORY_SEPARATOR.Channel::USER_AUTHENTICATED;

        return new Update(
            $topic,
            json_encode([
                'originatedMessageId' => $this->getOriginatedMessageId($message),
                'userId' => $userId,
                'token' => $token
            ])
        );
    }
}