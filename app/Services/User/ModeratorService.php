<?php

namespace App\Services\User;

use App\Enums\Users\RolesEnum;

class ModeratorService extends AbstractRoleService
{
    /** @var string $role */
    protected string $role = RolesEnum::MODERATOR;
}
