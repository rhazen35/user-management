<?php

declare(strict_types=1);

namespace App\Handler\User\Create;

use App\Entity\User\User;
use App\Mercure\User\UserCreatedUpdateFactory;
use App\Messenger\Dispatcher;
use App\Messenger\Event\User\UserCreatedEventFactory;
use App\Messenger\Message;

class UserCreatedMessageHandler
{
    private UserCreatedEventFactory $userCreatedEventFactory;
    private UserCreatedUpdateFactory $userCreatedUpdateFactory;
    private Dispatcher $dispatcher;

    public function __construct(
        UserCreatedEventFactory $userCreatedEventFactory,
        UserCreatedUpdateFactory $userCreatedUpdateFactory,
        Dispatcher $dispatcher
    ) {
        $this->userCreatedEventFactory = $userCreatedEventFactory;
        $this->userCreatedUpdateFactory = $userCreatedUpdateFactory;
        $this->dispatcher = $dispatcher;
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

        $update = $this
            ->userCreatedUpdateFactory
            ->create(
                $message,
                $user
            );

        $this
            ->dispatcher
            ->dispatchEventAndUpdate(
                $envelope,
                $update
            );
    }
}