<?php

declare(strict_types=1);

namespace App\Handler\User\Data;

use App\Mercure\User\Data\GetUsersResultUpdateFactory;
use App\Messenger\Dispatcher;
use App\Messenger\Message;
use App\Messenger\Query\User\GetUsersQueryResultFactory;
use App\View\Pagination\PaginationView;

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

    public function __invoke(
        Message $message,
        PaginationView $paginationView
    ): void {
        $envelope = $this
            ->getUsersQueryResultFactory
            ->create(
                $message,
                $paginationView
            );

        $update = $this
            ->getUsersResultUpdateFactory
            ->create(
                $message,
                $paginationView
            );

        $this
            ->dispatcher
            ->dispatchEventAndUpdate(
                $envelope,
                $update
            );
    }
}