<?php

declare(strict_types=1);

namespace App\Mercure\Authentication;

use App\Enum\Authentication\Channel;
use App\Mercure\Authentication\Contract\AbstractUpdateFactory;
use App\Messenger\Message;
use Symfony\Component\Mercure\Update;

class TokenExpiredUpdateFactory extends AbstractUpdateFactory
{
    public function create(
        Message $message,
        string $token
    ): Update {
        return new Update(
            $this->getTopic(Channel::TOKEN_EXPIRED),
            json_encode([
                'channel' => Channel::TOKEN_EXPIRED,
                'originatedMessageId' => $this->getOriginatedMessageId($message),
                'token' => $token
            ])
        );
    }
}