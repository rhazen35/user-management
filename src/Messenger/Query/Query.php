<?php

declare(strict_types=1);

namespace App\Messenger\Query;

use App\Messenger\Contract\QueryMessageInterface;
use App\Messenger\Internal\InternalMessageAwareInterface;
use App\Messenger\Internal\InternalMessageTrait;
use App\Messenger\Message;
use App\Messenger\Token\TokenAwareInterface;
use App\Messenger\Token\TokenTrait;

class Query extends Message implements
    QueryMessageInterface,
    TokenAwareInterface,
    InternalMessageAwareInterface
{
    use TokenTrait;
    use InternalMessageTrait;

    public function __construct(
        string $channel,
               $payload, string
               $messageId,
        ?string $originatedMessageId,
        bool $internal,
        ?string $token
    ) {
        parent::__construct(
            $channel,
            $payload,
            $messageId,
            $originatedMessageId
        );

        $this->internal = $internal;
        $this->token = $token;
    }
}