<?php

namespace App\Policies;

use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;
use App\Models\Affiliate;
use App\Models\User;

class AffiliatePolicy extends BasePolicy
{
    protected array $existsMethods = [
        'canViewAll' => Access::VIEW_LIST_AFFILIATES,
        'canCreate' => Access::CREATE_AFFILIATES,
    ];

    /**
     * @param User      $user
     * @param Affiliate $affiliate
     *
     * @return bool
     */
    public function canViewCurrent(User $user, Affiliate $affiliate): bool
    {
        return $this->can($user, Access::VIEW_CURRENT_AFFILIATES)
            && $this->containsCompanies($user, $affiliate);
    }

    /**
     * @param User      $user
     * @param Affiliate $affiliate
     *
     * @return bool
     */
    public function canUpdate(User $user, Affiliate $affiliate): bool
    {
        return $this->can($user, Access::UPDATE_AFFILIATES)
            && $this->containsCompanies($user, $affiliate);
    }

    /**
     * @param User      $user
     * @param Affiliate $affiliate
     *
     * @return bool
     */
    public function canDelete(User $user, Affiliate $affiliate): bool
    {
        return $this->can($user, Access::DELETE_AFFILIATES)
            && $this->containsCompanies($user, $affiliate);
    }
}
