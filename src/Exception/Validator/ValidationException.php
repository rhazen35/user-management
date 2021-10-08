<?php

declare(strict_types=1);

namespace App\Exception\Validator;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends RuntimeException
{
    /**
     * @var ConstraintViolationListInterface<ConstraintViolation>
     */
    private ConstraintViolationListInterface $constraintViolationList;
    private object $invalidObject;

    /**
     * ValidationException constructor.
     *
     * @param ConstraintViolationListInterface<ConstraintViolation> $constraintViolationList
     */
    public function __construct(string $message, ConstraintViolationListInterface $constraintViolationList, object $invalidObject, int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->constraintViolationList = $constraintViolationList;
        $this->invalidObject = $invalidObject;
    }

    /**
     * @param ConstraintViolationListInterface<ConstraintViolation> $violationList
     */
    public static function fromViolationList(ConstraintViolationListInterface $violationList, object $invalidObject): self
    {
        $type = get_class($invalidObject);

        return new self(
            sprintf(
                'There were constraint violations found for object of type %s',
                $type
            ),
            $violationList,
            $invalidObject
        );
    }

    /**
     * @return ConstraintViolationListInterface<ConstraintViolation>
     */
    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }

    public function getInvalidObject(): object
    {
        return $this->invalidObject;
    }
}