<?php

declare(strict_types=1);

namespace App\Model\User;

use App\DataFixtures\Development\UserFixtureData;
use App\Entity\User\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Factory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function create(): User
    {
        return new User();
    }

    public function createFromFixtureData(UserFixtureData $data): User
    {
        $user = $this->create();

        $password = $this
            ->passwordHasher
            ->hashPassword(
                $user,
                $data->getPassword()
            );

        return $user
            ->setFirstName($data->getFirstName())
            ->setLastName($data->getLastName())
            ->setUsername($data->getUserName())
            ->setEmail($data->getEmail())
            ->setPassword($password)
            ->eraseCredentials();
    }

    public function createFromData(CreateData $data): User
    {
        $user = $this->create();

        $password = $this
            ->passwordHasher
            ->hashPassword(
                $user,
                $data->getPassword()
            );

        return $user
            ->setFirstName($data->getFirstName())
            ->setLastName($data->getLastName())
            ->setUsername($data->getEmail())
            ->setEmail($data->getEmail())
            ->setPassword($password)
            ->eraseCredentials();
    }
}