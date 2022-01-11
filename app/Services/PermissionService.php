<?php

namespace App\Services;

use App\Enums\Users\RolesEnum;
use App\Events\RoleSync;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use LogicException;
use Spatie\Permission\PermissionRegistrar;

class PermissionService
{
    /**
     * @throws ModelNotFoundException
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return Role::query()
            ->with(['permissions' => fn($q) => $q->selectRaw('id, name')])
            ->selectRaw('id, name')
            ->get();
    }

    /**
     * @param User|null $user
     *
     * @return array
     */
    public function authPermissions(?User $user): array
    {
        if (!$user) {
            throw (new ModelNotFoundException())->setModel('User');
        }

        return $user->load([
            'roles' => fn($q) => $q->select(['id', 'name', 'guard_name']),
            'permissions' => fn($q) => $q->select(['id', 'name', 'guard_name']),
        ])->only('id', 'status', 'email', 'roles', 'permissions');
    }

    /**
     * @param int   $roleId
     * @param array $permissions
     *
     * @return Collection
     */
    public function syncRolePermission(int $roleId, array $permissions): Collection
    {
        /** @var Role $role */
        if (!$role = Role::query()->whereKey($roleId)->first()) {
            throw (new ModelNotFoundException())->setModel('Role', $roleId);
        }

        if ($role->name === RolesEnum::SUPER_ADMIN) {
            throw new LogicException('Can\'t update this role');
        }

        $role->permissions()->toggle($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        event(new RoleSync($role, $permissions));

        return $this->index();
    }
}
