<?php

declare(strict_types=1);

namespace App\Handler\User\Create;

use App\Enum\User\Channel;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\External\ExternalMessage;
use App\Messenger\Message;
use App\Model\User\CreateDataFactory;
use App\Model\User\CreateManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class CreateUserHandler implements HandlerInterface
{
    private CreateDataFactory $createUserDataFactory;
    private CreateUserValidatorHandler $createUserValidatorHandler;
    private CreateManager $createUserManager;
    private UserCreatedMessageHandler $userCreatedHandler;

    public function __construct(
        CreateDataFactory          $createUserDataFactory,
        CreateUserValidatorHandler $createUserValidatorHandler,
        CreateManager              $createUserManager,
        UserCreatedMessageHandler  $userCreatedHandler
    ) {
        $this->createUserDataFactory = $createUserDataFactory;
        $this->createUserValidatorHandler = $createUserValidatorHandler;
        $this->createUserManager = $createUserManager;
        $this->userCreatedHandler = $userCreatedHandler;
    }

    public function supports(Message $message): bool
    {
        return Channel::CREATE_USER === $message->getChannel();
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function __invoke(Message $message): void
    {
        assert($message instanceof ExternalMessage);

        $createUserData = $this
            ->createUserDataFactory
            ->createFromPayload($message->getPayload());

        $isValid = $this
            ->createUserValidatorHandler
            ->__invoke(
                $message,
                $createUserData
            );

        if (!$isValid) {
            return;
        }

        $user = $this
            ->createUserManager
            ->__invoke(
                $createUserData,
                $message
            );

        $this
            ->userCreatedHandler
            ->__invoke(
                $user,
                $message
            );
    }
}