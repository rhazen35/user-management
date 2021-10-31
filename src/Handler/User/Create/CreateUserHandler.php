<?php

declare(strict_types=1);

namespace App\Handler\User\Create;

use App\Enum\User\Channel;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\External\ExternalMessage;
use App\Messenger\Message;
use App\Model\User\CreateUserDataFactory;
use App\Model\User\Manager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class CreateUserHandler implements HandlerInterface
{
    private CreateUserDataFactory $createUserDataFactory;
    private CreateUserValidatorHandler $createUserValidatorHandler;
    private Manager $manager;
    private UserCreatedMessageHandler $userCreatedHandler;

    public function __construct(
        CreateUserDataFactory $createUserDataFactory,
        CreateUserValidatorHandler $createUserValidatorHandler,
        Manager $manager,
        UserCreatedMessageHandler $userCreatedHandler
    ) {
        $this->createUserDataFactory = $createUserDataFactory;
        $this->createUserValidatorHandler = $createUserValidatorHandler;
        $this->manager = $manager;
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
            ->manager
            ->createAndFlush(
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