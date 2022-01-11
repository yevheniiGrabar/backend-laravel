<?php

namespace App\Services\User;

use App\Enums\Users\RolesEnum;

class ClientService extends AbstractRoleService
{
    /** @var string $role */
    protected string $role = RolesEnum::CLIENT;
}
