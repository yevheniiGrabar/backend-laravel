<?php

namespace Database\Seeders;

use App\Classes\Permissions\Accessor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class SyncRolesAndPermissionsOnUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws \ReflectionException
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        $accessor = new Accessor();

        $roles->each(function (Role $role) use ($accessor) {
            $permissions = $accessor->getPermissions($role->name);
            $role->users()
                ->get()
                ->each(fn(User $user) => $user->syncPermissions($permissions));
        });
    }
}
