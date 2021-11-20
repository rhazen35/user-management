<?php

declare(strict_types=1);

namespace App\DataFixtures\Development;

class UserFixtureDataFactory
{
    public function create(
        string $firstName,
        string $lastName,
        string $userName,
        string $email,
        string $password
    ): UserFixtureData {
        return new UserFixtureData(
            $firstName,
            $lastName,
            $userName,
            $email,
            $password
        );
    }
}