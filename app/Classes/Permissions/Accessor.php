<?php

namespace App\Classes\Permissions;

use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;

class Accessor
{
    /**
     * @param array $access
     *
     * @return array
     */
    public function getClientAccess(array $access): array
    {
        return $access;
    }

    /**
     * @throws \ReflectionException
     *
     * @return array
     */
    public function getSuperAdminAccess(): array
    {
        return Access::getAllValues();
    }

    /**
     * @param array $access
     *
     * @return array
     */
    public function getAdminAccess(array $access): array
    {
        return array_filter($access, static function ($item) {
            return !in_array($item, [
                Access::EXPORTS_USERS,
                Access::EXPORTS_COMPANY_USERS,
                Access::UPDATE_COMPANY_SETTINGS,
                Access::UPDATE_CABINET,
                Access::VIEW_CABINET,
                Access::VIEW_LIST_FILES,
            ], true);
        });
    }

    /**
     * @param array $access
     *
     * @return array
     */
    public function getManagerAccess(array $access): array
    {
        return array_filter($access, static function ($item) {
            return !in_array($item, [
                Access::EXPORTS_USERS,
                Access::EXPORTS_COMPANY_USERS,
                Access::UPDATE_COMPANY_SETTINGS,
                Access::UPDATE_CABINET,
                Access::VIEW_CABINET,
                Access::DELETE_AFFILIATES,
                Access::UPDATE_AFFILIATES,
                Access::CREATE_AFFILIATES,
                Access::VIEW_LIST_AFFILIATES,
                Access::VIEW_CURRENT_AFFILIATES,
                Access::VIEW_LIST_FILES,
            ], true);
        });
    }

    /**
     * @return array
     */
    public function getModeratorAccess(): array
    {
        return [
            Access::DELETE_AFFILIATES,
            Access::UPDATE_AFFILIATES,
            Access::CREATE_AFFILIATES,
            Access::VIEW_LIST_AFFILIATES,
            Access::VIEW_CURRENT_AFFILIATES,
            Access::VIEW_LIST_CARDS,
            Access::VIEW_CURRENT_CARD,
            Access::UPDATE_CARDS,
            Access::DELETE_CARDS,
            Access::CREATE_CARDS,
            Access::VIEW_CURRENT_CALENDAR,
            Access::VIEW_LIST_EVENTS,
            Access::VIEW_CURRENT_USERS_EVENTS,
            Access::UPDATE_EVENTS,
            Access::CREATE_EVENTS,
            Access::DELETE_EVENTS,
            Access::VIEW_COMPANY_SETTINGS,
            Access::VIEW_LIST_USERS,
            Access::VIEW_LIST_COMPANY_USERS,
            Access::VIEW_LIST_COURSES,
            Access::VIEW_CURRENT_COURSE,
            Access::VIEW_LIST_DEPARTMENTS,
            Access::UPDATE_DEPARTMENTS,
            Access::DELETE_DEPARTMENTS,
            Access::CREATE_DEPARTMENTS,
            Access::VIEW_CURRENT_DEPARTMENT,
            Access::VIEW_USER_CONFIG,
            Access::VIEW_COMPANY_USER_CONFIG,
            Access::UPDATE_USERS,
            Access::UPDATE_COMPANY_USERS,
            Access::VIEW_TASKS,
            Access::UPDATE_CURRENT_USER,
            Access::UPDATE_CURRENT_COMPANY_USER,
            Access::CREATE_FILES,
        ];
    }

    /**
     * @return array
     */
    public function getStudentAccess(): array
    {
        return [
            Access::VIEW_LIST_AFFILIATES,
            Access::VIEW_CURRENT_AFFILIATES,
            Access::VIEW_LIST_CARDS,
            Access::VIEW_LIST_EVENTS,
            Access::VIEW_CURRENT_CARD,
            Access::VIEW_CURRENT_CALENDAR,
            Access::VIEW_LIST_COURSES,
            Access::VIEW_CURRENT_COURSE,
            Access::VIEW_CURRENT_DEPARTMENT,
            Access::VIEW_USER_CONFIG,
            Access::VIEW_COMPANY_USER_CONFIG,
            Access::UPDATE_CURRENT_COMPANY_USER,
            Access::VIEW_COMPANY_SETTINGS,
            Access::CREATE_FILES,
        ];
    }

    /**
     * @param string $role
     *
     * @throws \ReflectionException
     * @return array
     */
    public function getPermissions(string $role): array
    {
        $access = Access::getAll();

        $list = [
            RolesEnum::SUPER_ADMIN => fn() => $this->getSuperAdminAccess(),
            RolesEnum::MODERATOR => fn() => $this->getModeratorAccess(),
            RolesEnum::STUDENT => fn() => $this->getStudentAccess(),
            RolesEnum::ADMIN => fn() => $this->getAdminAccess($access),
            RolesEnum::CLIENT => fn() => $this->getClientAccess($access),
            RolesEnum::MANAGER => fn() => $this->getManagerAccess($access),
        ];

        return call_user_func($list[$role]);
    }
}
