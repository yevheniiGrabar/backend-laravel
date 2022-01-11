<?php

namespace App\Policies;

use App\Enums\Permissions\Access;

class CalendarEventPolicy extends BasePolicy
{
    protected array $existsMethods = [
        'canViewCurrent' => Access::VIEW_CURRENT_USERS_EVENTS,
        'canCreate' => Access::CREATE_EVENTS,
        'canUpdate' => Access::UPDATE_EVENTS,
        'canDelete' => Access::DELETE_EVENTS,
    ];
}
