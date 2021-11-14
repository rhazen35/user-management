<?php

declare(strict_types=1);

namespace App\Handler\Authentication;

use App\Factory\Authentication\Credentials;
use App\Messenger\Message;
use App\Validator\ValidationTrait;
use App\ViewTransformer\Validator\FormViolationListViewFactory;

class CredentialsValidatorHandler
{
    use ValidationTrait;

    private FormViolationListViewFactory $formViolationListViewFactory;
    private InvalidCredentialsMessageHandler $invalidCredentialsMessageHandler;

    public function __construct(
        FormViolationListViewFactory $formViolationListViewFactory,
        InvalidCredentialsMessageHandler $invalidCredentialsMessageHandler
    ) {
        $this->formViolationListViewFactory = $formViolationListViewFactory;
        $this->invalidCredentialsMessageHandler = $invalidCredentialsMessageHandler;
    }

    public function __invoke(
        Credentials $credentials,
        Message $message
    ): bool {
        $violations = $this->validate($credentials);

        if ($violations->count() > 0) {
            $violations = $this
                ->formViolationListViewFactory
                ->__invoke($violations);

            $this
                ->invalidCredentialsMessageHandler
                ->__invoke(
                    $message,
                    $violations
                );

            return false;
        }

        return true;
    }
}