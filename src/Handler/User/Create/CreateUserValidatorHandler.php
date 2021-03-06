<?php

declare(strict_types=1);

namespace App\Handler\User\Create;

use App\Messenger\Event\User\InvalidCreateUserEventFactory;
use App\Messenger\Message;
use App\Model\User\CreateData;
use App\Validator\ValidationTrait;
use App\ViewTransformer\Validator\FormViolationListViewFactory;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserValidatorHandler
{
    use ValidationTrait;

    private FormViolationListViewFactory $formViolationListViewFactory;
    private InvalidCreateUserEventFactory $invalidCreateUserEventFactory;
    private MessageBusInterface $eventBus;

    public function __construct(
        FormViolationListViewFactory $formViolationListViewFactory,
        InvalidCreateUserEventFactory $invalidCreateUserEventFactory,
        MessageBusInterface $eventBus
    ) {
        $this->formViolationListViewFactory = $formViolationListViewFactory;
        $this->invalidCreateUserEventFactory = $invalidCreateUserEventFactory;
        $this->eventBus = $eventBus;
    }

    public function __invoke(
        Message $message,
        CreateData $createUserData
    ): bool {
        $violations = $this->validate($createUserData);

        if ($violations->count() > 0) {
            $violations = $this
                ->formViolationListViewFactory
                ->__invoke($violations);

            $envelope = $this
                ->invalidCreateUserEventFactory
                ->create(
                    $message,
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