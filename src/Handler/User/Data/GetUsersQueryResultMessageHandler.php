<?php

declare(strict_types=1);

namespace App\Handler\User\Data;

use App\Entity\User\User;
use App\Mercure\User\Data\GetUsersResultUpdateFactory;
use App\Messenger\Dispatcher;
use App\Messenger\Message;
use App\Messenger\Query\User\GetUsersQueryResultFactory;

class GetUsersQueryResultMessageHandler
{
    private GetUsersQueryResultFactory $getUsersQueryResultFactory;
    private GetUsersResultUpdateFactory $getUsersResultUpdateFactory;
    private Dispatcher $dispatcher;

    public function __construct(
        GetUsersQueryResultFactory $getUsersQueryResultFactory,
        GetUsersResultUpdateFactory $getUsersResultUpdateFactory,
        Dispatcher $dispatcher
    ) {
        $this->getUsersQueryResultFactory = $getUsersQueryResultFactory;
        $this->getUsersResultUpdateFactory = $getUsersResultUpdateFactory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param array<User> $users
     */
    public function __invoke(
        Message $message,
        array $users
    ): void {
        $envelope = $this
            ->getUsersQueryResultFactory
            ->create(
                $message,
                $users
            );

        $update = $this
            ->getUsersResultUpdateFactory
            ->create(
                $message,
                $users
            );

        $this
            ->dispatcher
            ->dispatchEventAndUpdate(
                $envelope,
                $update
            );
    }
}