<?php

namespace App\Policies;

use App\Enums\Permissions\Access;
use App\Enums\Users\RolesEnum;
use App\Models\User;

class FilePolicy extends BasePolicy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function canViewAll(User $user): bool
    {
        return $user->hasDirectPermission(Access::VIEW_LIST_FILES);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function canCreate(User $user): bool
    {
        return $user->hasDirectPermission(Access::CREATE_FILES);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function canDelete(User $user): bool
    {
        return $user->hasDirectPermission(Access::DELETE_FILES);
    }
}
