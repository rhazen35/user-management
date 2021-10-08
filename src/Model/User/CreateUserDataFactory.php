<?php

declare(strict_types=1);

namespace App\Model\User;

class CreateUserDataFactory
{
    public function createArrayFromPayload(object $payload): array
    {
        return [
            'firstName' => $payload->firstName ?? null,
            'lastName' => $payload->lastName ?? null,
            'email' => $payload->email ?? null,
            'password' => $payload->password ?? null,
        ];
    }
}