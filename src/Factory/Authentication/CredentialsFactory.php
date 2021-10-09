<?php

declare(strict_types=1);

namespace App\Factory\Authentication;

class CredentialsFactory
{
    public function createFromPayload(object $payload): Credentials
    {
        $email = $payload->email ?? null;
        $password = $payload->password ?? null;

        return new Credentials($email, $password);
    }
}