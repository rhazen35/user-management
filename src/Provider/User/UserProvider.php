<?php

declare(strict_types=1);

namespace App\Provider\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Uid\UuidV4;

class UserProvider
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function getUserById(UuidV4 $id): User
    {
        return $this
            ->userRepository
            ->findOneById($id);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getUserOrNullById(UuidV4 $id): ?User
    {
        return $this
            ->userRepository
            ->findOneOrNullById($id);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getUserOrNullByEmail(string $email): ?User
    {
        return $this
            ->userRepository
            ->findOneOrNullByEmail($email);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getUserByEmailOrNull(string $email): ?User
    {
        return $this
            ->userRepository
            ->findOneOrNullByEmail($email);
    }

    /**
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this
            ->userRepository
            ->findOneByEmail($email);
    }
}

