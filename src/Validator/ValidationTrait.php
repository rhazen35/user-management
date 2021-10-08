<?php

declare(strict_types=1);

namespace App\Validator;

use App\Exception\Validator\ValidationException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidationTrait
{
    /**
     * @var ValidatorInterface
     *
     * We use public injection for less code
     */
    protected ValidatorInterface $validator;

    /**
     * @required
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * Validates a value against a constraint or a list of constraints.
     * Throws an exception, handled by ValidationExceptionResponseListener::class.
     *
     * @param object                   $value       The value to validate
     * @param array<Constraint>|null   $constraints The constraint(s) to validate against
     * @param (string|GroupSequence)[] $groups      The validation groups to validate. If none is given, "Default" is assumed
     *
     * @throws ValidationException
     */
    protected function ensureValid(object $value, array $constraints = null, array $groups = null): void
    {
        $constraintViolationList = $this
            ->validate(
                $value,
                $constraints,
                $groups
            );

        if (0 === $constraintViolationList->count()) {
            return;
        }

        throw ValidationException::fromViolationList($constraintViolationList, $value);
    }

    /**
     * @param array<Constraint>|null           $constraints
     * @param array<string|GroupSequence>|null $groups
     *
     * @return ConstraintViolationListInterface<ConstraintViolation>
     */
    public function validate(object $value, array $constraints = null, array $groups = null): ConstraintViolationListInterface
    {
        return $this
            ->validator
            ->validate(
                $value,
                $constraints,
                $groups
            );
    }
}