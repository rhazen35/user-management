<?php

declare(strict_types=1);

namespace App\Model\User;

class CreateData
{
    private ?string $firstName;
    private ?string $lastName;
    private ?string $username;
    private ?string $email;
    private ?string $password;

    public function __construct(
        ?string $firstName,
        ?string $lastName,
        ?string $username,
        ?string $email,
        ?string $password
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}