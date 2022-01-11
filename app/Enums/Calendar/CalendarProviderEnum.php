<?php

namespace App\Enums\Calendar;

use App\Enums\AbstractEnum;

class CalendarProviderEnum extends AbstractEnum
{
    // can be google, outlook, etc.
    public const INTERNAL = 'internal';
}
