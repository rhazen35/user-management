<?php

declare(strict_types=1);

namespace App\View\Validator;

class FormViolationView
{
    public string $message;
    public ?string $propertyPath;

    /**
     * @OA\Property(type="string")
     *
     * @var mixed
     */
    public $invalidValue;

    /**
     * @param mixed|null $invalidValue
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