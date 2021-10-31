<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Entity\User\User;
use App\Messenger\External\ExternalMessage;
use App\Provider\Authentication\TokenProvider;
use App\Provider\DateTime\DateTimeProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class DeleteManager
{
    private DateTimeProvider $dateTimeProvider;
    private TokenProvider $tokenProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(
        DateTimeProvider $dateTimeProvider,
        TokenProvider $tokenProvider,
        EntityManagerInterface $entityManager
    ) {
        $this->dateTimeProvider = $dateTimeProvider;
        $this->tokenProvider = $tokenProvider;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function __invoke(
        User $user,
        ExternalMessage $message
    ): User {
        $deletedBy = $this
            ->tokenProvider
            ->getUserFromToken($message->getToken());

        $deletedAt = $this
            ->dateTimeProvider
            ->getCurrentDateTimeImmutable();

        $user
            ->setDeletedAt($deletedAt)
            ->setDeletedBy($deletedBy);

        $this
            ->entityManager
            ->flush();

        return $user;
    }
}