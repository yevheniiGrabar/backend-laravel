<?php

namespace App\Policies;

use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;
use App\Models\CompanyPage;
use App\Models\User;

class CompanyPagePolicy extends BasePolicy
{
    /**
     * @param User $user
     * @param int  $companyId
     *
     * @return bool
     */
    public function canCreate(User $user, int $companyId): bool
    {
        return $user->hasDirectPermission(Access::CREATE_COMPANY_PAGES)
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
        return $user->hasDirectPermission(Access::UPDATE_COMPANY_PAGES)
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
        return $user->hasDirectPermission(Access::DELETE_COMPANY_PAGES)
            && $this->containEntity($user, $companyId);
    }

    /**
     * @param User $user
     * @param int  $pageId
     *
     * @return bool
     */
    private function containEntity(User $user, int $pageId): bool
    {
        if (!$page = CompanyPage::whereKey($pageId)->first()) {
            return false;
        }

        return $this->containsIdCompany($user, $page->entity_id);
    }
}
