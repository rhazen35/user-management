<?php

declare(strict_types=1);

namespace App\Handler\User\Delete;

use App\Enum\User\Channel;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\External\ExternalMessage;
use App\Messenger\Message;
use App\Model\User\DeleteManager;
use App\Provider\User\UserProvider;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Uid\UuidV4;

class DeleteUserHandler implements HandlerInterface
{
    private UserProvider $userProvider;
    private DeleteManager $deleteUserManager;
    private UserDeletedMessageHandler $userDeletedMessageHandler;

    public function __construct(
        UserProvider              $userProvider,
        DeleteManager             $deleteUserManager,
        UserDeletedMessageHandler $userDeletedMessageHandler
    ) {
        $this->userProvider = $userProvider;
        $this->deleteUserManager = $deleteUserManager;
        $this->userDeletedMessageHandler = $userDeletedMessageHandler;
    }

    public function supports(Message $message): bool
    {
        return Channel::DELETE_USER === $message->getChannel();
    }

    /**
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     * @throws JWTDecodeFailureException
     */
    public function __invoke(Message $message): void
    {
        assert($message instanceof ExternalMessage);

        $payload = $message->getPayload();
        $userId = $payload->id ?? null;

        if (null === $userId) {
            return;
        }

        $user = $this
            ->userProvider
            ->getUserById(UuidV4::fromRfc4122($userId));

        $this
            ->deleteUserManager
            ->__invoke(
                $user,
                $message
            );

        $this
            ->userDeletedMessageHandler
            ->__invoke(
                $user,
                $message
            );
    }
}