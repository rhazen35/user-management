<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Entity\User\User;

class UpdateData
{
    private ?User $user;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $email;

    public function __construct(
        ?User $user,
        ?string $firstName,
        ?string $lastName,
        ?string $email
    ) {
        $this->user = $user;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}