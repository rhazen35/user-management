<?php

declare(strict_types=1);

namespace App\Messenger\External;

class ExternalMessage
{
    private ?string $channel;
    private ?array $payload;

    public function __construct(
        ?string $channel,
        ?array $payload
    ) {
        $this->channel = $channel;
        $this->payload = $payload;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }
}