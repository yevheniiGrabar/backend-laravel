<?php

namespace Database\Seeders;

use App\Classes\Permissions\Accessor;
use App\Enums\Users\RolesEnum;
use App\Models\Company;
use App\Models\Course;
use App\Models\User;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\Console\Output\ConsoleOutput;

class SuperAdminUserSeed extends Seeder
{
    public const ADMIN_EMAIL = 'admin@logos.com';
    public const ADMIN_USER = 'logos';
    public const ADMIN_PASSWORD = 'password';

    public function __construct(protected ConsoleOutput $consoleOutput)
    {
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::query()->where('email', self::ADMIN_EMAIL)->forceDelete();

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        /** @var User $user */
        $user = User::create([
            'first_name' => 'Logos',
            'last_name' => 'Admin',
            'phone' => '+380000000000',
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
        ]);

        $user->assignRole(RolesEnum::SUPER_ADMIN);
        $user->givePermissionTo((new Accessor())->getSuperAdminAccess());

        $companies = $this->createCompanies($user->id);
        $this->assignCompaniesToUser($user, $companies);


        $user->companies()->get()->each(function (Company $company) {
            $this->callWith(AffiliateDepartmentsSeeder::class, [
                'company' => $company,
            ]);

            Course::create([
               'company_id' => $company->id,
               'title' => 'PHP Developer for company #' . $company->id . ": " . $company->title,
               'description' => 'Course for future developers',
            ]);
        });

        $this->runAdmin(self::ADMIN_PASSWORD);


        $this->consoleOutput
            ->writeln("<bg=green;fg=white;options=bold>Super Admin User:</> " . self::ADMIN_USER);
        $this->consoleOutput
            ->writeln("<bg=green;fg=white;options=bold>Super Admin User password:</> " . self::ADMIN_PASSWORD);
    }

    protected function createCompanies(int $ownerId): Collection
    {
        $companies = new Collection();

        $companies->push(Company::query()->create([
            'title' => 'Logos Admin company #1',
            'owner_id' => $ownerId
        ]));

        $companies->push(Company::query()->create([
            'title' => 'Logos Admin company #2',
            'owner_id' => $ownerId
        ]));

        return $companies;
    }

    /**
     * @param User $user
     * @param Collection<Company> $companies
     */
    protected function assignCompaniesToUser(User $user, Collection $companies)
    {
        $user->companies()->saveMany($companies);
    }

    protected function runAdmin(string $adminPassword): void
    {
        // create a user in admin panel.
        Administrator::truncate();
        Administrator::create([
            'username' => 'logos',
            'password' => Hash::make($adminPassword),
            'name' => 'Administrator',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name' => 'All permission',
                'slug' => '*',
                'http_method' => '',
                'http_path' => '*',
            ],
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'http_method' => 'GET',
                'http_path' => '/',
            ],
            [
                'name' => 'Login',
                'slug' => 'auth.login',
                'http_method' => '',
                'http_path' => "/auth/login\r\n/auth/logout",
            ],
            [
                'name' => 'User setting',
                'slug' => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path' => '/auth/setting',
            ],
            [
                'name' => 'Auth management',
                'slug' => 'auth.management',
                'http_method' => '',
                'http_path' => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
        ]);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => 'Dashboard',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
            ],
            [
                'parent_id' => 0,
                'order' => 2,
                'title' => 'Admin',
                'icon' => 'fa-tasks',
                'uri' => '',
            ],
            [
                'parent_id' => 2,
                'order' => 3,
                'title' => 'Users',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
            ],
            [
                'parent_id' => 2,
                'order' => 4,
                'title' => 'Roles',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
            ],
            [
                'parent_id' => 2,
                'order' => 5,
                'title' => 'Permission',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
            ],
            [
                'parent_id' => 2,
                'order' => 6,
                'title' => 'Menu',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
            ],
            [
                'parent_id' => 2,
                'order' => 7,
                'title' => 'Operation log',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
            ],
        ]);

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
    }
}
