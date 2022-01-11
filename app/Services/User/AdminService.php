<?php

namespace App\Services\User;

use App\Enums\Users\RolesEnum;

class AdminService extends AbstractRoleService
{
    /** @var string $role */
    protected string $role = RolesEnum::ADMIN;
}
