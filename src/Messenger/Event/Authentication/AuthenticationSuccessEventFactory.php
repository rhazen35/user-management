<?php

declare(strict_types=1);

namespace App\Messenger\Event\Authentication;

use App\Entity\User\User;
use App\Enum\Authentication\Channel;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\Event\Event;
use App\Messenger\External\ExternalMessage;
use Symfony\Component\Messenger\Envelope;

class AuthenticationSuccessEventFactory extends AbstractMessageFactory
{
    public function create(
        ExternalMessage $externalMessage,
        User $user,
        string $token
    ): Envelope {
        $channel = Channel::USER_AUTHENTICATED;
        $idStamp = $this->getIdStamp();

        $event = new Event(
            $channel,
            [
                'id' => $user->getId(),
                'token' => $token
            ],
            $idStamp->getId(),
            $this->getOriginatedMessageId($externalMessage)
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