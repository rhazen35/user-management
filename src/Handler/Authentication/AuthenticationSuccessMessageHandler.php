<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Entity\User\User;
use App\Mercure\Authentication\AuthenticationSuccessUpdateFactory;
use App\Messenger\Dispatcher;
use App\Messenger\Event\Authentication\AuthenticationSuccessEventFactory;
use App\Messenger\Message;

class AuthenticationSuccessMessageHandler
{
    private AuthenticationSuccessEventFactory $authenticationSuccessEventFactory;
    private AuthenticationSuccessUpdateFactory $authenticationSuccessUpdateFactory;
    private Dispatcher $dispatcher;

    public function __construct(
        AuthenticationSuccessEventFactory $authenticationSuccessEventFactory,
        AuthenticationSuccessUpdateFactory $authenticationSuccessUpdateFactory,
        Dispatcher $dispatcher
    ) {
        $this->authenticationSuccessEventFactory = $authenticationSuccessEventFactory;
        $this->authenticationSuccessUpdateFactory = $authenticationSuccessUpdateFactory;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(
        Message $message,
        User $user,
        string $token
    ): void {
        $envelope = $this
            ->authenticationSuccessEventFactory
            ->create(
                $message,
                $user,
                $token
            );

        $update = $this
            ->authenticationSuccessUpdateFactory
            ->create(
                $message,
                $user,
                $token
            );

        $this
            ->dispatcher
            ->dispatchEventAndUpdate(
                $envelope,
                $update
            );
    }
}