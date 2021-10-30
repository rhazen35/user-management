<?php

declare(strict_types=1);

namespace App\Handler\User\Update;

use App\Entity\User\User;
use App\Mercure\User\UserUpdatedUpdateFactory;
use App\Messenger\Dispatcher;
use App\Messenger\Event\User\UserUpdatedEventFactory;
use App\Messenger\Message;

class UserUpdatedMessageHandler
{
    private UserUpdatedEventFactory $userUpdatedEventFactory;
    private UserUpdatedUpdateFactory $userUpdatedUpdateFactory;
    private Dispatcher $dispatcher;

    public function __construct(
        UserUpdatedEventFactory $userUpdatedEventFactory,
        UserUpdatedUpdateFactory $userUpdatedUpdateFactory,
        Dispatcher $dispatcher
    ) {
        $this->userUpdatedEventFactory = $userUpdatedEventFactory;
        $this->userUpdatedUpdateFactory = $userUpdatedUpdateFactory;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(
        User $user,
        Message $message
    ) {
        $envelope = $this
            ->userUpdatedEventFactory
            ->create(
                $user,
                $message
            );

        $update = $this
            ->userUpdatedUpdateFactory
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