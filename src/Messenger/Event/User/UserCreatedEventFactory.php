<?php

declare(strict_types=1);

namespace App\Messenger\Event\User;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Enum\User\Properties;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\Event\Event;
use App\Messenger\Message;
use Symfony\Component\Messenger\Envelope;

class UserCreatedEventFactory extends AbstractMessageFactory
{
    public function create(
        User $user,
        ?Message $message
    ): Envelope {
        $channel = Channel::USER_CREATED;
        $idStamp = $this->getIdStamp();

        $userId = $user
            ->getId()
            ->toRfc4122();

        $originatedMessageId = null === $message
            ? null
            : $this->getOriginatedMessageId($message);

        $event = new Event(
            $channel,
            [Properties::ID => $userId],
            $idStamp->getId(),
            $originatedMessageId
        );

        return new Envelope(
            $event,
            [
                $this->getAmqpStamp($channel),
                $idStamp
            ]
        );
    }
}