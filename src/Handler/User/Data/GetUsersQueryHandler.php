<?php

declare(strict_types=1);

namespace App\Handler\User\Data;

use App\Entity\User\User;
use App\Enum\User\Channel;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\Message;
use App\Provider\User\UserProvider;
use App\ViewTransformer\User\UserViewTransformer;

class GetUsersQueryHandler implements HandlerInterface
{
    private UserProvider $userProvider;
    private UserViewTransformer $userViewTransformer;
    private GetUsersQueryResultMessageHandler $getUsersQueryResultMessageHandler;

    public function __construct(
        UserProvider $userProvider,
        UserViewTransformer $userViewTransformer,
        GetUsersQueryResultMessageHandler $getUsersQueryResultMessageHandler
    ) {
        $this->userProvider = $userProvider;
        $this->userViewTransformer = $userViewTransformer;
        $this->getUsersQueryResultMessageHandler = $getUsersQueryResultMessageHandler;
    }

    public function supports(Message $message): bool
    {
        return Channel::GET_USERS === $message->getChannel();
    }

    public function __invoke(Message $message): void
    {
        $users = $this
            ->userProvider
            ->getUsers();

        $userViews = array_map(
            fn (User $user) => $this
                ->userViewTransformer
                ->__invoke($user),
            $users
        );

        $this
            ->getUsersQueryResultMessageHandler
            ->__invoke(
                $message,
                $userViews
            );
    }
}