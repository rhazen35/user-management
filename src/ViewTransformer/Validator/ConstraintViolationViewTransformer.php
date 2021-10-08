<?php

declare(strict_types=1);

namespace App\ViewTransformer\Validator;

use App\View\Validator\ConstraintViolationView;
use Symfony\Component\Validator\ConstraintViolationInterface;

class ConstraintViolationViewTransformer
{
    public function transform(ConstraintViolationInterface $constraintViolation): ConstraintViolationView
    {
        return new ConstraintViolationView(
            (string) $constraintViolation->getMessage(),
            $constraintViolation->getPropertyPath(),
            $constraintViolation->getInvalidValue()
        );
    }
}