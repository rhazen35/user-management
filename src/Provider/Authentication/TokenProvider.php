<?php

declare(strict_types=1);

namespace App\Provider\Authentication;

use App\Entity\User\User;
use App\Provider\User\UserProvider;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class TokenProvider
{
    private JWTEncoderInterface $jwtEncoder;
    private UserProvider $userProvider;

    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        UserProvider $userProvider
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->userProvider = $userProvider;
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function getUserFromToken(string $token): User
    {
        $decoded = $this
            ->jwtEncoder
            ->decode($token);

        $email = $decoded['email'] ?? null;

        return $this
            ->userProvider
            ->getUserByEmail($email);
    }
}