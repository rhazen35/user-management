<?php

declare(strict_types=1);

namespace App\Enum\Authentication;

class Channel
{
    const AUTHENTICATE_USER = 'authenticate_user';
    const INVALID_CREDENTIALS = 'invalid_credentials';
    const USER_AUTHENTICATED = 'user_authenticated';
}