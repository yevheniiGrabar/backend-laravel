<?php

namespace App\Policies;

use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;
use App\Models\User;

class DepartmentPolicy extends BasePolicy
{
    protected array $existsMethods = [
        'canViewAll' => Access::VIEW_LIST_DEPARTMENTS,
    ];

    /**
     * @param User $user
     * @param int  $companyId
     *
     * @return bool
     */
    public function canViewCurrent(User $user, int $companyId): bool
    {
        return $user->hasDirectPermission(Access::VIEW_CURRENT_DEPARTMENT)
            && $this->containsIdCompany($user, $companyId);
    }

    /**
     * @param User $user
     * @param int  $companyId
     *
     * @return bool
     */
    public function canCreate(User $user, int $companyId): bool
    {
        return $user->hasDirectPermission(Access::CREATE_DEPARTMENTS)
            && $this->containsIdCompany($user, $companyId);
    }

    /**
     * @param User $user
     * @param int  $companyId
     *
     * @return bool
     */
    public function canDelete(User $user, int $companyId): bool
    {
        return $user->hasDirectPermission(Access::DELETE_DEPARTMENTS)
            && $this->containsIdCompany($user, $companyId);
    }

    /**
     * @param User $user
     * @param int  $companyId
     *
     * @return bool
     */
    public function canUpdate(User $user, int $companyId): bool
    {
        return $user->hasDirectPermission(Access::UPDATE_DEPARTMENTS)
            && $this->containsIdCompany($user, $companyId);
    }
}
