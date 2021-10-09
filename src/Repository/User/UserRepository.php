<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneOrNullById(UuidV4 $id): ?User
    {
        return $this
            ->createQueryBuilder('user')
            ->where('user.id = :id')
            ->setParameter('id', $id->toBinary())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function findOneById(UuidV4 $id): User
    {
        $user = $this->findOneOrNullById($id);

        if (null === $user) {
            throw new EntityNotFoundException(
                sprintf(
                    'A user with id "%s" could not be found.',
                    $id->toRfc4122()
                )
            );
        }

        return $user;
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