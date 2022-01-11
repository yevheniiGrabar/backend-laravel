<?php

namespace App\Services\User;

use App\Enums\Users\RolesEnum;

class StudentService extends AbstractRoleService
{
    /** @var string $role */
    protected string $role = RolesEnum::STUDENT;
}
