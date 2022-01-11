<?php

namespace App\Classes\Users;

use App\Contracts\IRole;
use App\Enums\Users\RolesEnum;
use App\Services\User\{
    AdminService,
    ClientService,
    ManagerService,
    ManyRolesService,
    ModeratorService,
    StudentService,
    SuperAdminService
};

/**
 * Class RolesFactory
 *
 * @package App\Classes\Users
 */
class RolesFactory
{
    public static array $map = [
        RolesEnum::MANAGER => ManagerService::class,
        RolesEnum::CLIENT => ClientService::class,
        RolesEnum::ADMIN => AdminService::class,
        RolesEnum::MODERATOR => ModeratorService::class,
        RolesEnum::STUDENT => StudentService::class,
        RolesEnum::SUPER_ADMIN => SuperAdminService::class,
    ];

    /**
     * @param array $roles
     *
     * @return IRole
     */
    public static function make(array $roles): IRole
    {
        if (!self::checkOnExist($roles)) {
            throw new \LogicException(sprintf('One of roles are incorrect: [%s]', implode(',', $roles)));
        }

        if (count($roles) === 1) {
            return new self::$map[current($roles)]();
        }

        return new ManyRolesService($roles);
    }

    /**
     * @param array $roles
     *
     * @return bool
     */
    private static function checkOnExist(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!isset(self::$map[$role])) {
                return false;
            }
        }

        return true;
    }
}
