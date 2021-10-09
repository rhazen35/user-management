<?php

declare(strict_types=1);

namespace App\Messenger\Event\Authentication\Token;

use App\Enum\Authentication\Channel;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\Event\Event;
use App\Messenger\Message;
use Symfony\Component\Messenger\Envelope;

class TokenExpiredEventFactory extends AbstractMessageFactory
{
    public function create(Message $message): Envelope
    {
        $channel = Channel::TOKEN_EXPIRED;
        $idStamp = $this->getIdStamp();

        $event = new Event(
            $channel,
            [],
            $idStamp->getId(),
            $this->getOriginatedMessageId($message)
        );

        return new Envelope(
            $event,
            [
                $this->getAmqpStamp($channel),
                $idStamp,
            ]
        );
    }
}