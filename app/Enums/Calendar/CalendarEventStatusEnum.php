<?php

namespace App\Enums\Calendar;

use App\Enums\AbstractEnum;

class CalendarEventStatusEnum extends AbstractEnum
{
    /**
     * This property defines the overall status or confirmation for the calendar component.
     *
     * @see https://tools.ietf.org/html/rfc5545#section-3.8.1.11
     */
    public const STATUS_TENTATIVE = 'tentative';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
}
