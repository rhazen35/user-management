<?php

declare(strict_types=1);

namespace App\DataFixtures\Development;

class UserFixtureData
{
    private string $firstName;
    private string $lastName;
    private string $userName;
    private string $email;
    private string $password;

    public function __construct(
        string $firstName,
        string $lastName,
        string $userName,
        string $email,
        string $password
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}