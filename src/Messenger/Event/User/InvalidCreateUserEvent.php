<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

declare(strict_types=1);

namespace App\Messenger\Event\User;

use App\Messenger\Contract\PublishMessageInterface;

class InvalidCreateUserEvent implements PublishMessageInterface
{
    private string $channel;
    private array $payload;
    private string $messageId;
    private string $originatedMessageId;

    public function __construct(
        string $channel,
        array $payload,
        string $messageId,
        string $originatedMessageId
    ) {
        $this->channel = $channel;
        $this->payload = $payload;
        $this->messageId = $messageId;
        $this->originatedMessageId = $originatedMessageId;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getOriginatedMessageId(): string
    {
        return $this->originatedMessageId;
    }
}