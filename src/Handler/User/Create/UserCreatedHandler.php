<?php

declare(strict_types=1);

namespace App\Handler\User\Create;

use App\Entity\User\User;
use App\Messenger\Event\User\UserCreatedEventFactory;
use App\Messenger\Message;
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
        Message $message
    ) {
        $envelope = $this
            ->userCreatedEventFactory
            ->create(
                $user,
                $message
            );

        $this
            ->eventBus
            ->dispatch($envelope);
    }
}