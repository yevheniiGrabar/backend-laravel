<?php

namespace App\Policies;

use App\Enums\Permissions\Access;
use App\Models\User;

class UserPolicy extends BasePolicy
{
    protected array $existsMethods = [
        'canViewAll' => Access::VIEW_LIST_COMPANY_USERS,
        'canCreate' => Access::CREATE_COMPANY_USERS,
        'canUpdate' => Access::UPDATE_COMPANY_USERS,
        'canDelete' => Access::DELETE_COMPANY_USERS,
        'canExport' => Access::EXPORTS_COMPANY_USERS,
    ];

    /**
     * @param User $user
     *
     * @return bool
     */
    public function canViewSelfConfig(User $user): bool
    {
        return $user->hasDirectPermission(Access::VIEW_COMPANY_USER_CONFIG)
            && $user->id === auth()->user()?->id;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function canUpdateCurrentUser(User $user): bool
    {
        return $user->hasDirectPermission(Access::UPDATE_COMPANY_USERS)
            || ($user->hasDirectPermission(Access::UPDATE_CURRENT_COMPANY_USER)
                && $user->id === auth()->user()?->id);
    }
}
