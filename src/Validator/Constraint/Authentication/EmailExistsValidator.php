<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Authentication;

use App\Provider\User\UserProvider;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailExistsValidator extends ConstraintValidator
{
    private UserProvider $userProvider;

    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @param mixed|string $value
     * @param Constraint|EmailExists $constraint
     *
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        assert($constraint instanceof EmailExists);

        if (null === $value) {
            return;
        }

        $exists = $this
            ->userProvider
            ->getUserOrNullByEmail($value);

        if (null !== $exists) {
            return;
        }

        $this
            ->context
            ->addViolation(
                $constraint->message,
                ['%email%' => $value]
            );
    }
}