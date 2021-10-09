<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Enum\Authentication\Channel;
use App\Factory\Authentication\CredentialsFactory;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\Message;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;

class AuthenticateUserHandler implements HandlerInterface
{
    private CredentialsFactory $credentialsFactory;
    private CredentialsValidatorHandler $credentialsValidatorHandler;
    private AuthenticationSuccessHandler $authenticationSuccessHandler;

    public function __construct(
        CredentialsFactory $credentialsFactory,
        CredentialsValidatorHandler $credentialsValidatorHandler,
        AuthenticationSuccessHandler $authenticationSuccessHandler
    ) {
        $this->credentialsFactory = $credentialsFactory;
        $this->credentialsValidatorHandler = $credentialsValidatorHandler;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
    }

    public function supports(Message $message): bool
    {
        return Channel::AUTHENTICATE_USER === $message->getChannel();
    }

    /**
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function __invoke(Message $message): void
    {
        $credentials = $this
            ->credentialsFactory
            ->createFromPayload($message->getPayload());

        $isValid = $this
            ->credentialsValidatorHandler
            ->__invoke(
                $credentials,
                $message
            );

        if (!$isValid) {
            return;
        }

        $this
            ->authenticationSuccessHandler
            ->__invoke(
                $credentials,
                $message
            );
    }
}