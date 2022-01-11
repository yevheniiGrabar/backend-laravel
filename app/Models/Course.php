<?php

namespace App\Models;

use App\Enums\Users\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, Builder};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/** @method static Builder own() */
class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'courses';

    public const STATUS_ENABLED = 'ENABLED';
    public const STATUS_DISABLED = 'DISABLED';

    public const SUPPORTED_STATUSES = [
        self::STATUS_ENABLED,
        self::STATUS_DISABLED
    ];

    protected $fillable = [
        'company_id',
        'department_id',
        'logo',
        'title',
        'description',
        'status',
    ];

    protected $appends = [
        'logo_url',
    ];

    protected $hidden = [
        'logo',
    ];


    public function scopeViewable(Builder $builder): Builder
    {
        /**
         * @var $user User
         */
        $user = Auth::user();

        if ($user->hasRole(RolesEnum::SUPER_ADMIN)) {
            return $builder;
        }

        $companies = $user->companies()->pluck('id')->toArray();

        return $builder->whereIn('company_id', $companies);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo
            ? \Storage::disk('public')->url($this->table . "/" . last(explode("/", $this->logo)))
            : null;
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function affiliates(): BelongsToMany
    {
        return $this->belongsToMany(Affiliate::class, 'course_affiliate');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function moderators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_moderator', 'course_id', 'moderator_id');
    }
}
