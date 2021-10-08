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

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserData
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private string $firstName;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private string $lastName;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private string $email;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private string $password;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}