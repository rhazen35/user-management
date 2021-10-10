<?php

declare(strict_types=1);

namespace App\Model\User;

class CreateUserDataFactory
{
    public function createFromPayload(object $payload): CreateUserData
    {
        $createUserData = new CreateUserData();

        return $createUserData
            ->setFirstName($payload->firstName ?? null)
            ->setLastName($payload->lastName ?? null)
            ->setEmail($payload->email ?? null)
            ->setPassword($payload->password ?? null);
    }
}