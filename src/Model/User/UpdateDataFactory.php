<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Enum\User\Properties;
use App\Provider\User\UserProvider;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Uid\UuidV4;

class UpdateDataFactory
{
    private UserProvider $userProvider;

    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function createFromPayload(
        object $payload
    ): UpdateData {
        $userId = !property_exists($payload, Properties::ID)
            ? null
            : UuidV4::fromRfc4122($payload->id);

        $user = null === $userId
            ? null
            : $this
                ->userProvider
                ->getUserOrNullById($userId);

        $firstName = !property_exists($payload, Properties::FIRST_NAME)
            ? $user->getFirstName()
            : $payload->firstName;

        $lastName = !property_exists($payload, Properties::LAST_NAME)
            ? $user->getLastName()
            : $payload->lastName;

        $email = !property_exists($payload, Properties::EMAIL)
            ? $user->getEmail()
            : $payload->email;

        return new UpdateData(
            $user,
            $firstName,
            $lastName,
            $email
        );
    }
}