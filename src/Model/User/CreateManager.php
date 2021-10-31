<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Entity\User\User;
use App\Messenger\External\ExternalMessage;
use App\Provider\Authentication\TokenProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class CreateManager
{
    private Factory $factory;
    private TokenProvider $tokenProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Factory $factory,
        TokenProvider $tokenProvider,
        EntityManagerInterface $entityManager
    ) {
        $this->factory = $factory;
        $this->tokenProvider = $tokenProvider;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function __invoke(
        CreateData      $data,
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
}