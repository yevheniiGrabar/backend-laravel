<?php

namespace App\Enums\Calendar;

use App\Enums\AbstractEnum;

class CalscaleEnum extends AbstractEnum
{
    /**
     * This property defines the calendar scale used for the calendar information specified in the iCalendar object.
     *
     * According to RFC 5545: 3.7.1. Calendar Scale
     *
     * @see http://tools.ietf.org/html/rfc5545#section-3.7
     */
    public const CALSCALE_GREGORIAN = 'GREGORIAN';
}
