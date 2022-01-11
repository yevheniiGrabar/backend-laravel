<?php

namespace App\Policies;

use App\Enums\Permissions\Access;

class CalendarPolicy extends BasePolicy
{
    protected array $existsMethods = [
        'canViewCurrent' => Access::VIEW_CURRENT_CALENDAR
    ];
}
