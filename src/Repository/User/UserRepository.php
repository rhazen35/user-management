<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneOrNullByEmail(string $email): ?User
    {
        return $this
            ->createQueryBuilder('user')
            ->where('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function findOneByEmail(string $email): User
    {
        $user = $this->findOneOrNullByEmail($email);

        if (null === $user) {
            throw new EntityNotFoundException(
                sprintf(
                    'A user with email "%s" could not be found. ',
                    $email
                )
            );
        }

        return $user;
    }
}