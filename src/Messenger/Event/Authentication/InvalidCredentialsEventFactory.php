<?php

declare(strict_types=1);

namespace App\Messenger\Event\Authentication;

use App\Enum\Authentication\Channel;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\Event\Event;
use App\Messenger\Message;
use Symfony\Component\Messenger\Envelope;

class InvalidCredentialsEventFactory extends AbstractMessageFactory
{
    public function create(
        Message $message,
        array $violations
    ): Envelope {
        $channel = Channel::INVALID_CREDENTIALS;
        $idStamp = $this->getIdStamp();

        $event = new Event(
            $channel,
            ['violations' => $violations],
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