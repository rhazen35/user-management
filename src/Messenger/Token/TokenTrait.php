<?php

declare(strict_types=1);

namespace App\Messenger\Token;

trait TokenTrait
{
    private ?string $token;

    public function getToken(): ?string
    {
        return $this->token;
    }
}