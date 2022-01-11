<?php

namespace App\Enums\Calendar;

use App\Enums\AbstractEnum;
use App\Models\Company;
use App\Models\User;

class CalendarOwnerEnum extends AbstractEnum
{
    public const USER = User::class;
    public const COMPANY = Company::class;
}
