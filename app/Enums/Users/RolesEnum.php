<?php

namespace App\Enums\Users;

use App\Enums\AbstractEnum;

class RolesEnum extends AbstractEnum
{
    public const ADMIN = 'admin';
    public const CLIENT = 'client';
    public const MANAGER = 'manager';
    public const MODERATOR = 'moderator';
    public const STUDENT = 'student';
    public const SUPER_ADMIN = 'super_admin';
}
