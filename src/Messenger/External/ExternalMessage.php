<?php

declare(strict_types=1);

namespace App\Messenger\External;

use App\Messenger\Contract\SubscribeMessageInterface;
use App\Messenger\Message;

final class ExternalMessage extends Message implements SubscribeMessageInterface
{
    private string $token;

    public function __construct(
        string $channel,
               $payload,
        string $messageId,
        ?string $originatedMessageId,
        string $token
    ) {
        parent::__construct(
            $channel,
            $payload,
            $messageId,
            $originatedMessageId
        );

        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}