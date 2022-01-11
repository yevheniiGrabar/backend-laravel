<?php

namespace App\Services\User;

use App\Classes\Permissions\Accessor;
use App\Contracts\IRole;
use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;
use App\Models\User;

abstract class AbstractRoleService implements IRole
{
    /** @var string $role */
    protected string $role = RolesEnum::SUPER_ADMIN;

    /** @var User $user */
    protected User $user;

    /**
     * @param User $user
     *
     * @throws \ReflectionException
     *
     * @return User
     */
    public function setRolesAndPermissions(User $user): User
    {
        return $this->assignThisRole($user)
            ->syncPermissionsWithRole();
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function assignThisRole(User $user): self
    {
        $user->assignRole($this->role);

        $this->user = $user;

        return $this;
    }

    /**
     * @throws \ReflectionException
     *
     * @return User
     */
    public function syncPermissionsWithRole(): User
    {
        return $this->user->givePermissionTo($this->getPermissions());
    }

    /**
     * @throws \ReflectionException
     *
     * @return array
     */
    protected function getPermissions(): array
    {
        return (new Accessor())->getPermissions($this->role);
    }
}
