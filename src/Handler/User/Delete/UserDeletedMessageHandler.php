<?php

declare(strict_types=1);

namespace App\Handler\User\Delete;

use App\Entity\User\User;
use App\Mercure\User\UserDeletedUpdateFactory;
use App\Messenger\Dispatcher;
use App\Messenger\Event\User\UserDeletedEventFactory;
use App\Messenger\Message;

class UserDeletedMessageHandler
{
    private UserDeletedEventFactory $userDeletedEventFactory;
    private UserDeletedUpdateFactory $userDeletedUpdateFactory;
    private Dispatcher $dispatcher;

    public function __construct(
        UserDeletedEventFactory $userDeletedEventFactory,
        UserDeletedUpdateFactory $userDeletedUpdateFactory,
        Dispatcher $dispatcher
    ) {
        $this->userDeletedEventFactory = $userDeletedEventFactory;
        $this->userDeletedUpdateFactory = $userDeletedUpdateFactory;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(
        User $user,
        Message $message
    ): void {
        $envelope = $this
            ->userDeletedEventFactory
            ->create(
                $user,
                $message
            );

        $update = $this
            ->userDeletedUpdateFactory
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