<?php

declare(strict_types=1);

namespace App\Model\User;

class CreateDataFactory
{
    public function createFromPayload(object $payload): CreateData
    {
        return new CreateData(
            $payload->firstName ?? null,
            $payload->lastName ?? null,
            $payload->username ?? null,
            $payload->email ?? null,
            $payload->password ?? null,
        );
    }
}