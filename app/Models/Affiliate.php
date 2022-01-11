<?php

namespace App\Models;

use App\Enums\Users\RolesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

/** @property Company $company */
class Affiliate extends Model
{
    use HasFactory;

    public $timestamps = true;

    public $fillable = [
        'title',
        'company_id'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'affiliate_department');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_affiliate');
    }

    public function scopeViewable(Builder $builder)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole(RolesEnum::SUPER_ADMIN)) {
            return $builder;
        }

        return $builder->whereIn('company_id', $user->companies()->pluck('id')->toArray());
    }
}
