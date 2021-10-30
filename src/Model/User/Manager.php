<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Entity\User\User;
use App\Provider\DateTime\DateTimeProvider;
use Doctrine\ORM\EntityManagerInterface;

class Manager
{
    private Factory $factory;
    private DateTimeProvider $dateTimeProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Factory $factory,
        DateTimeProvider $dateTimeProvider,
        EntityManagerInterface $entityManager
    ) {
        $this->factory = $factory;
        $this->dateTimeProvider = $dateTimeProvider;
        $this->entityManager = $entityManager;
    }

    public function createAndFlush(CreateUserData $data): User
    {
        $user = $this
            ->factory
            ->createFromData($data);

        $this
            ->entityManager
            ->persist($user);

        $this
            ->entityManager
            ->flush();

        return $user;
    }

    public function updateAndFlush(UpdateUserData $data): User
    {
        $user = $data->getUser();

        $updatedAt = $this
            ->dateTimeProvider
            ->getCurrentDateTimeImmutable();

        $user
            ->setFirstName($data->getFirstName())
            ->setLastName($data->getLastName())
            ->setEmail($data->getEmail())
            ->setUpdatedAt($updatedAt);

        $this
            ->entityManager
            ->flush();

        return $user;
    }
}