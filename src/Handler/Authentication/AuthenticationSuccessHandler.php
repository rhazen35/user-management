<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Factory\Authentication\Credentials;
use App\Messenger\Message;
use App\Provider\User\UserProvider;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as LexikSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthenticationSuccessHandler
{
    private UserProvider $userProvider;
    private JWTTokenManagerInterface $jwtManager;
    private LexikSuccessHandler $authenticationSuccessHandler;
    private LoginHandler $loginHandler;
    private AuthenticationSuccessMessageHandler $authenticationSuccessMessageHandler;

    public function __construct(
        UserProvider $userProvider,
        JWTTokenManagerInterface $jwtManager,
        LexikSuccessHandler $authenticationSuccessHandler,
        LoginHandler $loginHandler,
        AuthenticationSuccessMessageHandler $authenticationSuccessMessageHandler
    ) {
        $this->userProvider = $userProvider;
        $this->jwtManager = $jwtManager;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
        $this->loginHandler = $loginHandler;
        $this->authenticationSuccessMessageHandler = $authenticationSuccessMessageHandler;
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

        $this
            ->loginHandler
            ->__invoke(
                $user,
                $message
            );

        $this
            ->authenticationSuccessMessageHandler
            ->__invoke(
                $message,
                $user,
                $token
            );
    }
}