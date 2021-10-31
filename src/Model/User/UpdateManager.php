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

class UpdateManager
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
        UpdateData      $data,
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
}