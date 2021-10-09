<?php

declare(strict_types=1);

namespace App\Enum\Authentication;

class WhiteList
{
    const AUTHENTICATE_USER = 'authenticate_user';

    /**
     * @return string[]
     */
    public static function getList(): array
    {
        return [self::AUTHENTICATE_USER];
    }
}