<?php

namespace App\Services\User;

use App\Enums\Users\RolesEnum;

class ManagerService extends AbstractRoleService
{
    /** @var string $role */
    protected string $role = RolesEnum::MANAGER;
}
