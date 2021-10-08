<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Messenger\Event\User\InvalidCreateUserEventFactory;
use App\Messenger\External\ExternalMessage;
use App\ViewTransformer\Validator\FormViolationListViewFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserValidatorHandler
{
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
        ExternalMessage $externalMessage,
        FormInterface $form
    ): bool {
        if (!$form->isValid()) {
            $violations = $this
                ->formViolationListViewFactory
                ->flattenForForm($form);

            $envelope = $this
                ->invalidCreateUserEventFactory
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