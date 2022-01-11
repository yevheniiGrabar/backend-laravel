<?php

namespace Database\Seeders;

use App\Classes\Permissions\Accessor;
use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    public function run(): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        //TODO can be grouped in future
//        $permissionRaw = [
//            PermissionsGroupEnum::AFFILIATES => [
//                Access::VIEW_LIST_AFFILIATES,
//                Access::VIEW_CURRENT_AFFILIATES,
//                Access::CREATE_AFFILIATES,
//                Access::UPDATE_AFFILIATES,
//                Access::DELETE_AFFILIATES
//            ],
//            PermissionsGroupEnum::CALENDARS => [
//                Access::VIEW_LIST_CALENDARS,
//                Access::VIEW_CURRENT_CALENDAR
//            ],
//            PermissionsGroupEnum::CARDS => [
//                Access::VIEW_LIST_CARDS,
//                Access::UPDATE_CARDS,
//                Access::CREATE_CARDS,
//                Access::DELETE_CARDS
//            ],
//            PermissionsGroupEnum::COMPANY => [
//                Access::EDIT_COMPANY_SETTINGS,
//                Access::VIEW_COMPANY_SETTINGS
//            ],
//            PermissionsGroupEnum::COURSES => [
//                Access::CREATE_COURSES,
//                Access::UPDATE_COURSES,
//                Access::DELETE_COURSES,
//                Access::VIEW_LIST_COURSES
//            ],
//            PermissionsGroupEnum::DEPARTMENTS => [
//                Access::DELETE_DEPARTMENTS,
//                Access::CREATE_DEPARTMENTS,
//                Access::UPDATE_DEPARTMENTS,
//                Access::VIEW_LIST_DEPARTMENTS
//            ],
//            PermissionsGroupEnum::EVENTS => [
//                Access::CREATE_EVENTS,
//                Access::DELETE_EVENTS,
//                Access::UPDATE_EVENTS,
//                Access::VIEW_LIST_EVENTS
//            ],
//            PermissionsGroupEnum::FILES => [
//                Access::VIEW_LIST_FILES,
//            ],
//            PermissionsGroupEnum::LESSONS => [
//                Access::CREATE_LESSON,
//                Access::DELETE_LESSON,
//                Access::UPDATE_LESSON,
//                Access::VIEW_LIST_LESSON
//            ],
//            PermissionsGroupEnum::PERMISSIONS_SETTINGS => [
//                Access::EDIT_PERMISSION_SETTINGS,
//                Access::VIEW_PERMISSION_SETTINGS
//            ],
//            PermissionsGroupEnum::QUIZZES => [
//                Access::VIEW_LIST_QUIZZES,
//                Access::CREATE_QUIZZES,
//                Access::DELETE_QUIZZES,
//                Access::UPDATE_QUIZZES
//            ],
//            PermissionsGroupEnum::USER_LIST => [
//                Access::CREATE_USERS,
//                Access::EXPORTS_USERS,
//                Access::UPDATE_USERS,
//                Access::DELETE_USERS,
//                Access::VIEW_LIST_USERS
//            ],
//        ];

        $permissionList = Access::getAllValues();
        $accessor = new Accessor();

        foreach ($permissionList as $permission) {
            Permission::query()->firstOrcreate(['name' => $permission, 'guard_name' => 'api']);
        }

        $roles = RolesEnum::getAllValuesAsKeys();

        foreach ($roles as $role => $key) {
            $roles[$role] = Role::query()->firstOrCreate(['name' => $role, 'guard_name' => 'api']);
        }

        $roles[RolesEnum::STUDENT]->syncPermissions($accessor->getStudentAccess());

        $roles[RolesEnum::MODERATOR]->syncPermissions($accessor->getModeratorAccess());

        $roles[RolesEnum::MANAGER]->syncPermissions($accessor->getManagerAccess($permissionList));

        $roles[RolesEnum::ADMIN]->syncPermissions($accessor->getAdminAccess($permissionList));

        $roles[RolesEnum::CLIENT]->syncPermissions($accessor->getClientAccess($permissionList));

        $roles[RolesEnum::SUPER_ADMIN]->syncPermissions($accessor->getSuperAdminAccess());
    }
}
