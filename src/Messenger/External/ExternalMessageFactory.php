<?php

declare(strict_types=1);

namespace App\Messenger\External;

use Exception;

class ExternalMessageFactory
{
    /**
     * @throws Exception
     */
    public function createFromArray(array $data): ExternalMessage
    {
        $channel = $data['channel'] ?? null;

        if (null === $channel) {
            throw new Exception("The channel is not provided.");
        }

        $payload = $data['payload'] ?? null;

        if (null === $payload) {
            throw new Exception("The payload is not provided.");
        }

        return new ExternalMessage($channel, $payload);
    }
}