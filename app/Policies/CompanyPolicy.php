<?php

namespace App\Policies;

use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;
use App\Models\User;

class CompanyPolicy extends BasePolicy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function canWorkWithCompany(User $user): bool
    {
        //TODO need change this, when logic for company will appear
        return true;
    }

    /**
     * @param User $user
     * @param int  $id
     *
     * @return bool
     */
    public function canViewCurrent(User $user, int $id): bool
    {
        return $this->can($user, Access::VIEW_COMPANY_SETTINGS)
            && $this->containsIdCompany($user, $id);
    }

    /**
     * @param User $user
     * @param int  $id
     *
     * @return bool
     */
    public function canUpdate(User $user, int $id): bool
    {
        return $this->can($user, Access::UPDATE_COMPANY_SETTINGS)
            && $this->containsIdCompany($user, $id);
    }
}
