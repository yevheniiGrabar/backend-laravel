<?php

namespace Tests\Feature\Api\Traits;

use App\Classes\Permissions\Accessor;
use App\Enums\Users\RolesEnum;
use App\Models\Company;
use App\Models\User;
use Laravel\Passport\Passport;

trait AuthAsUser
{
    protected function authAsUser(array $routes, array $roles = [RolesEnum::STUDENT])
    {
        if (empty($user)) {
            /** @var User $user */
            $user = User::factory(1)->create()->first();

            $user->roles()->delete();
            $accessor = new Accessor();

            foreach ($roles as $role) {
                $user->assignRole($role);
                $user->syncPermissions($accessor->getPermissions($role));
            }

            if (Company::query()->get()->count()) {
                $user->companies()->sync(Company::all()->pluck('id')->toArray());
            } else {
                $companies = Company::factory(2)->create(['owner_id' => $user->id]);

                $user->companies()->sync($companies->pluck('id')->toArray());
            }

            $user->save();
        }
        Passport::actingAs(
            $user,
            $routes
        );
    }
}
