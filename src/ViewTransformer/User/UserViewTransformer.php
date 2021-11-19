<?php

declare(strict_types=1);

namespace App\ViewTransformer\User;

use App\Entity\User\User;
use App\View\User\UserView;

class UserViewTransformer
{
    public function __invoke(User $user): UserView
    {
        return new UserView($user);
    }
}