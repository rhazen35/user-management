<?php

declare(strict_types=1);

namespace App\View\User;

use App\Entity\User\User;

class UserView
{
    public string $id;
    public string $firstName;
    public string $lastName;
    public string $fullName;
    public string $username;
    public string $email;
    public ?string $lastLogin;
    public string $createdAt;

    public function __construct(User $user)
    {
        $this->id = $user
            ->getId()
            ->toRfc4122();

        $lastLogin = null === $user->getLastLogin()
            ? null
            : $user
                ->getLastLogin()
                ->format('c');

        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->fullName = $this->firstName . " " .$this->lastName;
        $this->username = $user->getUsername();
        $this->email = $user->getEmail();
        $this->lastLogin = $lastLogin;

        $this->createdAt = $user
            ->getCreatedAt()
            ->format('c');
    }
}