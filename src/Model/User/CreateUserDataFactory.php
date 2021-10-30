<?php

declare(strict_types=1);

namespace App\Model\User;

class CreateUserDataFactory
{
    public function createFromPayload(object $payload): CreateUserData
    {
        return new CreateUserData(
            $payload->firstName ?? null,
            $payload->lastName ?? null,
            $payload->email ?? null,
            $payload->password ?? null,
        );
    }
}