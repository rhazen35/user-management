<?php

declare(strict_types=1);

namespace App\Handler\User\Update;

use App\Enum\User\Channel;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\External\ExternalMessage;
use App\Messenger\Message;
use App\Model\User\Manager;
use App\Model\User\UpdateUserDataFactory;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class UpdateUserHandler implements HandlerInterface
{
    private UpdateUserDataFactory $updateUserDataFactory;
    private UpdateUserValidatorHandler $updateUserValidatorHandler;
    private Manager $manager;
    private UserUpdatedMessageHandler $userUpdatedMessageHandler;

    public function __construct(
        UpdateUserDataFactory $updateUserDataFactory,
        UpdateUserValidatorHandler $updateUserValidatorHandler,
        Manager $manager,
        UserUpdatedMessageHandler $userUpdatedMessageHandler
    ) {
        $this->updateUserDataFactory = $updateUserDataFactory;
        $this->updateUserValidatorHandler = $updateUserValidatorHandler;
        $this->manager = $manager;
        $this->userUpdatedMessageHandler = $userUpdatedMessageHandler;
    }

    public function supports(Message $message): bool
    {
        return Channel::UPDATE_USER === $message->getChannel();
    }

    /**
     * @throws NonUniqueResultException
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function __invoke(Message $message): void
    {
        assert($message instanceof ExternalMessage);

        $updateUserData = $this
            ->updateUserDataFactory
            ->createFromPayload($message->getPayload());

        $isValid = $this
            ->updateUserValidatorHandler
            ->__invoke(
                $message,
                $updateUserData
            );

        if (!$isValid) {
            return;
        }

        $user = $this
            ->manager
            ->updateAndFlush(
                $updateUserData,
                $message
            );

        $this
            ->userUpdatedMessageHandler
            ->__invoke(
                $user,
                $message
            );
    }
}