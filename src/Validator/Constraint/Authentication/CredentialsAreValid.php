<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Authentication;

use Symfony\Component\Validator\Constraint;

class CredentialsAreValid extends Constraint
{
    public string $message = "The given credentials are not valid.";

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}