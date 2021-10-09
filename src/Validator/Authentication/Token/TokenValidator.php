<?php

declare(strict_types=1);

namespace App\Validator\Authentication\Token;

use App\Entity\User\User;
use App\Messenger\Event\Authentication\Token\InvalidTokenEventFactory;
use App\Messenger\Event\Authentication\Token\TokenExpiredEventFactory;
use App\Messenger\Message;
use App\Messenger\Token\TokenAwareInterface;
use App\Provider\User\UserProvider;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Messenger\MessageBusInterface;

class TokenValidator
{
    private JWTEncoderInterface $jwtEncoder;
    private UserProvider $userProvider;
    private InvalidTokenEventFactory $invalidTokenEventFactory;
    private TokenExpiredEventFactory $tokenExpiredEventFactory;
    private MessageBusInterface $eventBus;

    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        UserProvider $userProvider,
        InvalidTokenEventFactory $invalidTokenEventFactory,
        TokenExpiredEventFactory $tokenExpiredEventFactory,
        MessageBusInterface $eventBus
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->userProvider = $userProvider;
        $this->invalidTokenEventFactory = $invalidTokenEventFactory;
        $this->tokenExpiredEventFactory = $tokenExpiredEventFactory;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(Message $message): bool
    {
        assert($message instanceof TokenAwareInterface);

        $token = $message->getToken();

        try {
            $decoded = $this
                ->jwtEncoder
                ->decode($token);

            return null !== $this->getUser($decoded, $message);

        } catch (JWTDecodeFailureException $exception) {

            if (JWTDecodeFailureException::EXPIRED_TOKEN === $exception->getReason()) {
                $envelope = $this
                    ->tokenExpiredEventFactory
                    ->create($message);

                $this
                    ->eventBus
                    ->dispatch($envelope);

                return false;
            }

            $this->sendInvalidTokenEvent($message);

            return false;
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    private function getUser(
        array $decoded,
        Message $message
    ): ?User {
        $email = $decoded['email'] ?? null;

        if (null === $email) {
            $this->sendInvalidTokenEvent($message);

            return null;
        }

        $user = $this
            ->userProvider
            ->getUserOrNullByEmail($email);

        if (null === $user) {
            $this->sendInvalidTokenEvent($message);

            return null;
        }

        return $user;
    }

    private function sendInvalidTokenEvent(Message $message): void
    {
        $envelope = $this
            ->invalidTokenEventFactory
            ->create($message);

        $this
            ->eventBus
            ->dispatch($envelope);
    }
}