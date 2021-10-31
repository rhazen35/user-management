<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Entity\User\User;
use App\Model\User\Manager;
use App\Provider\DateTime\DateTimeProvider;

class LoginHandler
{
    private DateTimeProvider $dateTimeProvider;
    private Manager $manager;

    public function __construct(
        DateTimeProvider $dateTimeProvider,
        Manager $manager
    ) {
        $this->dateTimeProvider = $dateTimeProvider;
        $this->manager = $manager;
    }

    public function __invoke(User $user): void
    {
        $lastLogin = $user->getCurrentLogin();
        $currentLogin = $this
            ->dateTimeProvider
            ->getCurrentDateTimeImmutable();

        $this
            ->manager
            ->updateLoginAndFlush(
                $user,
                $currentLogin,
                $lastLogin
            );
    }
}