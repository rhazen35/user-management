<?php

declare(strict_types=1);

namespace App\Enum\User;

class Channel
{
    // Create
    const CREATE_USER = 'create_user';
    const INVALID_CREATE_USER = 'invalid_create_user';
    const USER_CREATED = 'user_created';

    // Update
    const UPDATE_USER = 'update_user';
    const INVALID_UPDATE_USER = 'invalid_update_user';
    const USER_UPDATED = 'user_updated';

    // Data
    const GET_USER = 'get_user';
    const GET_USER_RESULT = 'get_user_result';
}