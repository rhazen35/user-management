<?php

declare(strict_types=1);

namespace App\View\Validator;

use Doctrine\Common\Collections\Collection;

class ConstraintViolationListView
{
    /**
     * @var ConstraintViolationView[]
     */
    public array $violations;

    /**
     * @param Collection<int, ConstraintViolationView> $violations
     */
    public function __construct(Collection $violations)
    {
        assert($violations->forAll(fn ($i, $v) => $v instanceof ConstraintViolationView));
        $this->violations = $violations->getValues();
    }
}