<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

declare(strict_types=1);

namespace App\Messenger\Event\User;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Messenger\Contract\AbstractMessageFactory;
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

        $event = new UserCreatedEvent(
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