<?php

declare(strict_types=1);

namespace App\Handler\User\Update;

use App\Messenger\Event\User\InvalidUpdateUserEventFactory;
use App\Messenger\Message;
use App\Model\User\UpdateData;
use App\Validator\ValidationTrait;
use App\ViewTransformer\Validator\FormViolationListViewFactory;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateUserValidatorHandler
{
    use ValidationTrait;

    private FormViolationListViewFactory $formViolationListViewFactory;
    private InvalidUpdateUserEventFactory $invalidUpdateUserEventFactory;
    private MessageBusInterface $eventBus;

    public function __construct(
        FormViolationListViewFactory $formViolationListViewFactory,
        InvalidUpdateUserEventFactory $invalidUpdateUserEventFactory,
        MessageBusInterface $eventBus
    ) {
        $this->formViolationListViewFactory = $formViolationListViewFactory;
        $this->invalidUpdateUserEventFactory = $invalidUpdateUserEventFactory;
        $this->eventBus = $eventBus;
    }

    public function __invoke(
        Message $message,
        UpdateData $updateUserData
    ): bool {
        $violations = $this->validate($updateUserData);

        if ($violations->count() > 0) {
            $violations = $this
                ->formViolationListViewFactory
                ->__invoke($violations);

            $envelope = $this
                ->invalidUpdateUserEventFactory
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