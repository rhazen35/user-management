<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Authentication;

use Symfony\Component\Validator\Constraint;

class EmailExists extends Constraint
{
    public string $message = "constraint.authentication.email_does_not_exists";
}