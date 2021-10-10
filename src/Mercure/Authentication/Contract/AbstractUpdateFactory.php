<?php

declare(strict_types=1);

namespace App\Mercure\Authentication\Contract;

use App\Messenger\Message;

class AbstractUpdateFactory
{
    protected string $mercurePublicUrl;

    public function __construct(string $mercurePublicUrl)
    {
        $this->mercurePublicUrl = $mercurePublicUrl;
    }

    protected function getTopic(string $channel): string
    {
        return $this->mercurePublicUrl.DIRECTORY_SEPARATOR.$channel;
    }

    protected function getOriginatedMessageId(Message $message): ?string
    {
        return $message->getOriginatedMessageId() ?? $message->getMessageId();
    }
}