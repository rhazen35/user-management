<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

declare(strict_types=1);

namespace App\Handler\User;

use App\Entity\User\User;
use App\Messenger\Event\User\UserCreatedEventFactory;
use App\Messenger\External\ExternalMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class UserCreatedHandler
{
    private UserCreatedEventFactory $userCreatedEventFactory;
    private MessageBusInterface $eventBus;

    public function __construct(
        UserCreatedEventFactory $userCreatedEventFactory,
        MessageBusInterface $eventBus
    ) {
        $this->userCreatedEventFactory = $userCreatedEventFactory;
        $this->eventBus = $eventBus;
    }

    public function __invoke(
        User $user,
        ExternalMessage $externalMessage
    ) {
        $envelope = $this
            ->userCreatedEventFactory
            ->create(
                $user,
                $externalMessage
            );

        $this
            ->eventBus
            ->dispatch($envelope);
    }
}