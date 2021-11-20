<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Enum\Authentication\Channel;
use App\Handler\Contract\HandlerInterface;
use App\Mercure\Authentication\TokenExpiredUpdateFactory;
use App\Messenger\Dispatcher;
use App\Messenger\Message;

class TokenExpiredHandler implements HandlerInterface
{
    private Dispatcher $dispatcher;
    private TokenExpiredUpdateFactory $tokenExpiredUpdateFactory;

    public function __construct(
        Dispatcher $dispatcher,
        TokenExpiredUpdateFactory $tokenExpiredUpdateFactory
    ) {
        $this->dispatcher = $dispatcher;
        $this->tokenExpiredUpdateFactory = $tokenExpiredUpdateFactory;
    }

    public function supports(Message $message): bool
    {
        return Channel::TOKEN_EXPIRED === $message->getChannel();
    }

    public function __invoke(Message $message): void
    {
        $payload = $message->getPayload();
        $token = $payload['token'];
        $update = $this
            ->tokenExpiredUpdateFactory
            ->create(
                $message,
                $token
            );

        $this
            ->dispatcher
            ->dispatchUpdate($update);
    }
}