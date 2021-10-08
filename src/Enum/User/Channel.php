<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

declare(strict_types=1);

namespace App\Enum\User;

class Channel
{
    const CREATE_USER = 'create_user';
    const INVALID_CREATE_USER = 'invalid_create_user';
    const USER_CREATED = 'user_created';
}