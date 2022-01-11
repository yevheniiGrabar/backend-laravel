<?php

namespace App\Models;

use App\Contracts\ICalendar;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id          Identifier of the calendar.
 * @property integer $owner_id   The owner of the calendar.
 * @property integer $owner_type The owner type of the calendar.
 * @property string $summary     Title of the calendar.
 * @property string $description Description of the calendar.
 * @property string $timezone    The time zone of the calendar.
 * @property string $created_at  Date of creation.
 * @property string $updated_at  Date of updating.
 *
 * @property $owner
 */
class Calendar extends Model implements ICalendar
{
    use HasFactory;
    use UsesUuid;

    /** @var array $guarded */
    protected $guarded = [];

    /** @var string $keyType */
    protected $keyType = 'string';

    /** @var bool $incrementing */
    public $incrementing = false;

    /** @var string $table */
    protected $table = "calendars";

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all events from calendar
     *
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(CalendarEvent::class, 'calendar_id', 'id');
    }
}
