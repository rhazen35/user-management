<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Factory\Authentication\Credentials;
use App\Messenger\Event\Authentication\AuthenticationSuccessEventFactory;
use App\Messenger\Message;
use App\Provider\User\UserProvider;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as LexikSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class AuthenticationSuccessHandler
{
    private UserProvider $userProvider;
    private JWTTokenManagerInterface $jwtManager;
    private LexikSuccessHandler $authenticationSuccessHandler;
    private AuthenticationSuccessEventFactory $authenticationSuccessEventFactory;
    private MessageBusInterface $eventBus;

    public function __construct(
        UserProvider $userProvider,
        JWTTokenManagerInterface $jwtManager,
        LexikSuccessHandler $authenticationSuccessHandler,
        AuthenticationSuccessEventFactory $authenticationSuccessEventFactory,
        MessageBusInterface $eventBus
    ) {
        $this->userProvider = $userProvider;
        $this->jwtManager = $jwtManager;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
        $this->authenticationSuccessEventFactory = $authenticationSuccessEventFactory;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function __invoke(
        Credentials $credentials,
        Message $message
    ): void {
        $user = $this
            ->userProvider
            ->getUserByEmail($credentials->getEmail());

        $token = $this
            ->jwtManager
            ->create($user);

        $this
            ->authenticationSuccessHandler
            ->handleAuthenticationSuccess(
                $user,
                $token
            );

        $envelope = $this
            ->authenticationSuccessEventFactory
            ->create(
                $message,
                $user,
                $token
            );

        $this
            ->eventBus
            ->dispatch($envelope);
    }
}