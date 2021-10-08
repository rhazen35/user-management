<?php

declare(strict_types=1);

namespace App\Validator\Constraint\User;

use Symfony\Component\Validator\Constraint;

class EmailIsNotInUse extends Constraint
{
    public string $message = "constraint.user.email_in_use";
}