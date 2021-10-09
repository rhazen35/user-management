<?php

declare(strict_types=1);

namespace App\Messenger;

class Message
{
    private string $channel;

    /**
     * @var object|array
     */
    private $payload;
    private string $messageId;
    private ?string $originatedMessageId;

    /**
     * @param object|array $payload
     */
    public function __construct(
        string $channel,
        $payload,
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

    /**
     * @return array|object
     */
    public function getPayload()
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