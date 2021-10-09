<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Authentication;

use App\Factory\Authentication\Credentials;
use App\Provider\User\UserProvider;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CredentialsAreValidValidator extends ConstraintValidator
{
    private UserProvider $userProvider;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        UserProvider $userProvider,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->userProvider = $userProvider;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @param mixed|Credentials              $value
     * @param Constraint|CredentialsAreValid $constraint
     *
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        assert($constraint instanceof CredentialsAreValid);
        assert($value instanceof Credentials);

        if (null === $value->getEmail() || null === $value->getPassword()) {
            return;
        }

        $user = $this
            ->userProvider
            ->getUserByEmail($value->getEmail());

        $validPassword = $this
            ->userPasswordHasher
            ->isPasswordValid(
                $user,
                $value->getPassword()
            );

        if ($validPassword) {
            return;
        }

        $this
            ->context
            ->addViolation($constraint->message);
    }
}