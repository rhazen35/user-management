<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Entity\User\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class UpdateLoginManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(
        User $user,
        DateTimeImmutable $currentLogin,
        ?DateTimeImmutable $lastLogin
    ): User {
        $user
            ->setLastLogin($lastLogin)
            ->setCurrentLogin($currentLogin);

        $this
            ->entityManager
            ->flush();

        return $user;
    }
}