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

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

class Manager
{
    private Factory $factory;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Factory $factory,
        EntityManagerInterface $entityManager
    ) {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
    }

    public function createAndFlush(CreateUserData $data): User
    {
        $user = $this
            ->factory
            ->createFromData($data);

        $this
            ->entityManager
            ->persist($user);

        $this
            ->entityManager
            ->flush();

        return $user;
    }
}