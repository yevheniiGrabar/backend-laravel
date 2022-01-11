<?php

namespace App\Enums\Calendar;

use App\Enums\AbstractEnum;

//full list DateTimeZone::listIdentifiers(DateTimeZone::ALL)
class CalendarTimeZoneEnum extends AbstractEnum
{
    public const UTC = 'UTC';
    public const AMERICA_NY = 'America/New_York';
    public const EUROPE_KIEV = 'Europe/Kiev';
    public const EUROPE_MOSCOW = 'Europe/Moscow';
}
