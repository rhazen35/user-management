<?php

declare(strict_types=1);

namespace App\Messenger\Event\User;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\Event\Event;
use App\Messenger\External\ExternalMessage;
use Symfony\Component\Messenger\Envelope;

class UserCreatedEventFactory extends AbstractMessageFactory
{
    public function create(
        User $user,
        ExternalMessage $externalMessage
    ): Envelope {
        $channel = Channel::USER_CREATED;
        $idStamp = $this->getIdStamp();

        $userId = $user
            ->getId()
            ->toRfc4122();

        $event = new Event(
            $channel,
            ['userId' => $userId],
            $idStamp->getId(),
            $this->getOriginatedMessageId($externalMessage)
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