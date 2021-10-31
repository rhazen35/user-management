<?php

declare(strict_types=1);

namespace App\Enum\User;

class Channel
{
    // Create
    const CREATE_USER = 'create_user';
    const INVALID_CREATE_USER = 'invalid_create_user';
    const USER_CREATED = 'user_created';

    // Read
    const GET_USER = 'get_user';
    const GET_USER_RESULT = 'get_user_result';

    // Update
    const UPDATE_USER = 'update_user';
    const INVALID_UPDATE_USER = 'invalid_update_user';
    const USER_UPDATED = 'user_updated';

    // Delete
    const DELETE_USER = 'delete_user';
    const USER_DELETED = 'user_deleted';
}