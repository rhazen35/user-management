<?php

declare(strict_types=1);

namespace App\Handler\User\Create;

use App\Enum\User\Channel;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\Message;
use App\Model\User\CreateUserDataFactory;
use App\Model\User\Manager;

class CreateUserHandler implements HandlerInterface
{
    private CreateUserDataFactory $createUserDataFactory;
    private CreateUserValidatorHandler $createUserValidatorHandler;
    private Manager $manager;
    private UserCreatedHandler $userCreatedHandler;

    public function __construct(
        CreateUserDataFactory $createUserDataFactory,
        CreateUserValidatorHandler $createUserValidatorHandler,
        Manager $manager,
        UserCreatedHandler $userCreatedHandler
    ) {
        $this->createUserDataFactory = $createUserDataFactory;
        $this->createUserValidatorHandler = $createUserValidatorHandler;
        $this->manager = $manager;
        $this->userCreatedHandler = $userCreatedHandler;
    }

    public function supports(Message $message): bool
    {
        if (Channel::CREATE_USER !== $message->getChannel()) {
            return false;
        }

        return true;
    }

    public function __invoke(Message $message): void
    {
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
            ->createAndFlush($createUserData);

        $this
            ->userCreatedHandler
            ->__invoke(
                $user,
                $message
            );
    }
}