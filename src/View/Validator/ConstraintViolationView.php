<?php

declare(strict_types=1);

namespace App\View\Validator;

class ConstraintViolationView
{
    public string $message;

    public ?string $propertyPath;

    /**
     * @var mixed
     */
    public $invalidValue;

    /**
     * @param mixed $invalidValue
     */
    public function __construct(
        string $message,
        ?string $propertyPath,
        $invalidValue
    ) {
        $this->message = $message;
        $this->propertyPath = $propertyPath;
        $this->invalidValue = $invalidValue;
    }
}