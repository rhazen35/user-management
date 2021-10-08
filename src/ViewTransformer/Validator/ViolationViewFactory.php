<?php

declare(strict_types=1);

namespace App\ViewTransformer\Validator;

use App\View\Validator\FormViolationView;
use Symfony\Component\Validator\ConstraintViolation;

class ViolationViewFactory
{
    public function __invoke(ConstraintViolation $violation): FormViolationView
    {
        return new FormViolationView(
            (string) $violation->getMessage(),
            $violation->getPropertyPath(),
            $violation->getInvalidValue()
        );
    }

    public function fromFormError(
        ConstraintViolation $violation,
        string $fieldName
    ): FormViolationView {
        return new FormViolationView(
            (string) $violation->getMessage(),
            $fieldName,
            $violation->getInvalidValue()
        );
    }
}