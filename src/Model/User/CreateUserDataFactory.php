<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

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