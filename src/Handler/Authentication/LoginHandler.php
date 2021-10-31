<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Entity\User\User;
use App\Handler\User\Update\UserUpdatedMessageHandler;
use App\Messenger\Message;
use App\Model\User\UpdateLoginManager;
use App\Provider\DateTime\DateTimeProvider;

class LoginHandler
{
    private DateTimeProvider $dateTimeProvider;
    private UpdateLoginManager $updateLoginManager;
    private UserUpdatedMessageHandler $userUpdatedMessageHandler;

    public function __construct(
        DateTimeProvider $dateTimeProvider,
        UpdateLoginManager $updateLoginManager,
        UserUpdatedMessageHandler $userUpdatedMessageHandler
    ) {
        $this->dateTimeProvider = $dateTimeProvider;
        $this->updateLoginManager = $updateLoginManager;
        $this->userUpdatedMessageHandler = $userUpdatedMessageHandler;
    }

    public function __invoke(
        User $user,
        Message $message
    ): void {
        $lastLogin = $user->getCurrentLogin();
        $currentLogin = $this
            ->dateTimeProvider
            ->getCurrentDateTimeImmutable();

        $this
            ->updateLoginManager
            ->__invoke(
                $user,
                $currentLogin,
                $lastLogin
            );

        $this
            ->userUpdatedMessageHandler
            ->__invoke(
                $user,
                $message
            );
    }
}