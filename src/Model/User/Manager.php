<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Entity\User\User;
use App\Messenger\External\ExternalMessage;
use App\Messenger\Message;
use App\Provider\Authentication\TokenProvider;
use App\Provider\DateTime\DateTimeProvider;
use App\Provider\User\UserProvider;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Manager
{
    private Factory $factory;
    private DateTimeProvider $dateTimeProvider;
    private TokenProvider $tokenProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Factory $factory,
        DateTimeProvider $dateTimeProvider,
        TokenProvider $tokenProvider,
        EntityManagerInterface $entityManager
    ) {
        $this->factory = $factory;
        $this->dateTimeProvider = $dateTimeProvider;
        $this->tokenProvider = $tokenProvider;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function createAndFlush(
        CreateUserData $data,
        ExternalMessage $message
    ): User {
        $user = $this
            ->factory
            ->createFromData($data);

        $createdBy = $this
            ->tokenProvider
            ->getUserFromToken($message->getToken());

        $user->setCreatedBy($createdBy);

        $this
            ->entityManager
            ->persist($user);

        $this
            ->entityManager
            ->flush();

        return $user;
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function updateAndFlush(
        UpdateUserData $data,
        ExternalMessage $message
    ): User {
        $user = $data->getUser();

        $updatedAt = $this
            ->dateTimeProvider
            ->getCurrentDateTimeImmutable();

        $updatedBy = $this
            ->tokenProvider
            ->getUserFromToken($message->getToken());

        $user
            ->setFirstName($data->getFirstName())
            ->setLastName($data->getLastName())
            ->setEmail($data->getEmail())
            ->setUpdatedAt($updatedAt)
            ->setUpdatedBy($updatedBy);

        $this
            ->entityManager
            ->flush();

        return $user;
    }

    public function updateLoginAndFlush(
        User $user,
        DateTimeImmutable $currentLogin,
        ?DateTimeImmutable $lastLogin
    ): User {
        $user
            ->setLastLogin($lastLogin)
            ->setCurrentLogin($currentLogin);

        $this
            ->entityManager
            ->flush();

        return $user;
    }
}