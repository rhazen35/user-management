<?php

declare(strict_types=1);

namespace App\Model\User;

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

    public function createFromArray(array $data): User
    {
        $user = $this->create();

        $password = $this
            ->passwordHasher
            ->hashPassword(
                $user,
                $data['password']
            );

        return $user
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setUsername($data['username'])
            ->setEmail($data['email'])
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