<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Mercure\Authentication\InvalidCredentialsUpdateFactory;
use App\Messenger\Dispatcher;
use App\Messenger\Event\Authentication\InvalidCredentialsEventFactory;
use App\Messenger\Message;
use App\View\Validator\FormViolationView;

class InvalidCredentialsMessageHandler
{
    private InvalidCredentialsEventFactory $invalidCredentialsEventFactory;
    private InvalidCredentialsUpdateFactory $invalidCredentialsUpdateFactory;
    private Dispatcher $dispatcher;

    public function __construct(
        InvalidCredentialsEventFactory  $invalidCredentialsEventFactory,
        InvalidCredentialsUpdateFactory $invalidCredentialsUpdateFactory,
        Dispatcher                      $dispatcher
    ) {
        $this->invalidCredentialsEventFactory = $invalidCredentialsEventFactory;
        $this->invalidCredentialsUpdateFactory = $invalidCredentialsUpdateFactory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param array<FormViolationView> $violations
     */
    public function __invoke(
        Message $message,
        array $violations
    ): void {
        $envelope = $this
            ->invalidCredentialsEventFactory
            ->create(
                $message,
                $violations
            );

        $update = $this
            ->invalidCredentialsUpdateFactory
            ->create(
                $message,
                $violations
            );

        $this
            ->dispatcher
            ->dispatchEventAndUpdate(
                $envelope,
                $update
            );
    }
}