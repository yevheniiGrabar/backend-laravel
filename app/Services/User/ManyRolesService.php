<?php

namespace App\Services\User;

use App\Models\User;

class ManyRolesService extends AbstractRoleService
{
    /**
     * ManyRolesService constructor.
     *
     * @param array $roles
     */
    public function __construct(protected array $roles)
    {
    }

    /**
     * @param User $user
     *
     * @throws \ReflectionException
     *
     * @return User
     */
    public function setRolesAndPermissions(User $user): User
    {
        return $this->assignManyRoles($user)
            ->syncManyPermissions();
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function assignManyRoles(User $user): self
    {
        $user->assignRole($this->roles);

        $this->user = $user;

        return $this;
    }

    /**
     * @throws \ReflectionException
     *
     * @return User
     */
    public function syncManyPermissions(): User
    {
        $permissions = [];

        foreach ($this->roles as $role) {
            $this->role = $role;
            $permissions[] = $this->getPermissions();
        }

        return $this->user->syncPermissions(array_merge(...$permissions));
    }
}
