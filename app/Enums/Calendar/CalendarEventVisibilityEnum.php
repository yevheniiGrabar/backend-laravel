<?php

namespace App\Enums\Calendar;

use App\Enums\AbstractEnum;

class CalendarEventVisibilityEnum extends AbstractEnum
{
    /**
     * This property defines the access classification for a calendar component.
     *
     * @see https://tools.ietf.org/html/rfc5545#section-3.8.1.3
     */
    public const PUBLIC       = 'public';
    public const PRIVATE      = 'private';
    public const CONFIDENTIAL = 'confidential';
}
