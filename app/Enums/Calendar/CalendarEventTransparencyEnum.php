<?php

namespace App\Enums\Calendar;

use App\Enums\AbstractEnum;

class CalendarEventTransparencyEnum extends AbstractEnum
{
    /**
     * This property defines whether or not an event is transparent to busy time searches.
     *
     * @see https://tools.ietf.org/html/rfc5545#section-3.8.2.7
     */
    public const TRANSP_OPAQUE      = 'opaque';
    public const TRANSP_TRANSPARENT = 'transparent';
}
