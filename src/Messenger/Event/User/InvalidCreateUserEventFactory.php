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

use App\Enum\User\Channel;
use App\Messenger\Contract\AbstractMessageFactory;
use App\Messenger\External\ExternalMessage;
use Symfony\Component\Messenger\Envelope;

class InvalidCreateUserEventFactory extends AbstractMessageFactory
{
    public function create(
        ExternalMessage $externalMessage,
        array $violations
    ): Envelope {
        $channel = Channel::INVALID_CREATE_USER;
        $idStamp = $this->getIdStamp();

        $event = new InvalidCreateUserEvent(
            $channel,
            ['violations' => $violations],
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