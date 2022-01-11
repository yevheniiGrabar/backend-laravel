<?php

namespace App\Policies;

use App\Enums\Permissions\Access;
use App\Models\User;

class PermissionPolicy extends BasePolicy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function canViewAll(User $user): bool
    {
        return $user->hasDirectPermission(Access::VIEW_PERMISSION_SETTINGS);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function canUpdate(User $user): bool
    {
        return $user->hasDirectPermission(Access::UPDATE_PERMISSION_SETTINGS);
    }
}
