<?php

declare(strict_types=1);

namespace App\Messenger\External;

use App\Messenger\Contract\SubscribeMessageInterface;

class ExternalMessage implements SubscribeMessageInterface
{
    private string $channel;
    private object $payload;
    private string $messageId;
    private ?string $originatedMessageId;

    public function __construct(
        string $channel,
        object $payload,
        string $messageId,
        ?string $originatedMessageId
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

    public function getPayload(): object
    {
        return $this->payload;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getOriginatedMessageId(): ?string
    {
        return $this->originatedMessageId;
    }
}