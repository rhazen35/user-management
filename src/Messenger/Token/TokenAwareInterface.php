<?php

declare(strict_types=1);

namespace App\Messenger\Token;

interface TokenAwareInterface
{
    public function getToken(): ?string;
}