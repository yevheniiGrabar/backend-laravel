<?php

namespace App\Policies;

use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;
use App\Models\Company;
use App\Models\User;

class CoursePolicy extends BasePolicy
{
    protected array $existsMethods = [
        'canCreate' => Access::CREATE_COURSES,
        'canViewAll' => Access::VIEW_LIST_COURSES
    ];

    /**
     * @param User    $user
     * @param Company $company
     *
     * @return bool
     */
    public function canViewCurrent(User $user, Company $company): bool
    {
        return $user->hasDirectPermission(Access::VIEW_CURRENT_COURSE)
            && $this->containsIdCompany($user, $company->id);
    }

    /**
     * @param User    $user
     * @param Company $company
     *
     * @return bool
     */
    public function canDelete(User $user, Company $company): bool
    {
        return $user->hasDirectPermission(Access::DELETE_COURSES)
            && $this->containsIdCompany($user, $company->id);
    }

    /**
     * @param User    $user
     * @param Company $company
     *
     * @return bool
     */
    public function canUpdate(User $user, Company $company): bool
    {
        return $user->hasDirectPermission(Access::UPDATE_COURSES)
            && $this->containsIdCompany($user, $company->id);
    }
}
