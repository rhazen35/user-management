<?php

declare(strict_types=1);

namespace App\Messenger\External;

use App\Messenger\Contract\SubscribeMessageInterface;
use App\Messenger\Message;
use App\Messenger\Token\TokenAwareInterface;
use App\Messenger\Token\TokenTrait;

final class ExternalMessage extends Message implements
    SubscribeMessageInterface,
    TokenAwareInterface
{
    use TokenTrait;

    public function __construct(
        string $channel,
               $payload,
        string $messageId,
        ?string $originatedMessageId,
        ?string $token
    ) {
        parent::__construct(
            $channel,
            $payload,
            $messageId,
            $originatedMessageId
        );

        $this->token = $token;
    }
}