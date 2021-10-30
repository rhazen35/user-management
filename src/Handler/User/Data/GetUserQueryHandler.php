<?php

declare(strict_types=1);

namespace App\Handler\User\Data;

use App\Enum\User\Channel;
use App\Enum\User\Properties;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\Message;
use App\Messenger\Query\User\GetUserQueryResultFactory;
use App\Provider\User\UserProvider;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\UuidV4;

class GetUserQueryHandler implements HandlerInterface
{
    private UserProvider $userProvider;
    private GetUserQueryResultFactory $getUserQueryResultFactory;
    private MessageBusInterface $queryBus;

    public function __construct(
        UserProvider $userProvider,
        GetUserQueryResultFactory $getUserQueryResultFactory,
        MessageBusInterface $queryBus
    ) {
        $this->userProvider = $userProvider;
        $this->getUserQueryResultFactory = $getUserQueryResultFactory;
        $this->queryBus = $queryBus;
    }

    public function supports(Message $message): bool
    {
        return Channel::GET_USER === $message->getChannel();
    }

    /**
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function __invoke(Message $message): void
    {
        $payload = $message->getPayload();
        $id = $payload[Properties::ID] ?? null;

        if (null === $id) {
            return;
        }

        $uuid = UuidV4::fromRfc4122($id);

        $user = $this
            ->userProvider
            ->getUserById($uuid);

        $envelope = $this
            ->getUserQueryResultFactory
            ->create(
                $message,
                $user
            );

        $this
            ->queryBus
            ->dispatch($envelope);
    }
}