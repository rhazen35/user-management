<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Factory\Authentication\Credentials;
use App\Messenger\Event\Authentication\InvalidCredentialsEventFactory;
use App\Messenger\External\ExternalMessage;
use App\Validator\ValidationTrait;
use App\ViewTransformer\Validator\FormViolationListViewFactory;
use Symfony\Component\Messenger\MessageBusInterface;

class CredentialsValidatorHandler
{
    use ValidationTrait;

    private FormViolationListViewFactory $formViolationListViewFactory;
    private InvalidCredentialsEventFactory $invalidCredentialsEventFactory;
    private MessageBusInterface $eventBus;

    public function __construct(
        FormViolationListViewFactory $formViolationListViewFactory,
        InvalidCredentialsEventFactory $invalidCredentialsEventFactory,
        MessageBusInterface $eventBus
    ) {
        $this->formViolationListViewFactory = $formViolationListViewFactory;
        $this->invalidCredentialsEventFactory = $invalidCredentialsEventFactory;
        $this->eventBus = $eventBus;
    }

    public function __invoke(
        Credentials $credentials,
        ExternalMessage $externalMessage
    ): bool {
        $violations = $this->validate($credentials);

        if ($violations->count() > 0) {
            $violations = $this
                ->formViolationListViewFactory
                ->__invoke($violations);

            $envelope = $this
                ->invalidCredentialsEventFactory
                ->create(
                    $externalMessage,
                    $violations
                );

            $this
                ->eventBus
                ->dispatch($envelope);

            return false;
        }

        return true;
    }
}