<?php

namespace App\Models;

use App\Enums\Users\RolesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};
use Illuminate\Support\Facades\Auth;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'company_id',
        'title'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function affiliates(): BelongsToMany
    {
        return $this->belongsToMany(Affiliate::class, 'affiliate_department');
    }

    public function scopeViewable(Builder $builder)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();

        if ($user->hasRole(RolesEnum::SUPER_ADMIN)) {
            return $builder;
        }

        return $builder->whereIn('company_id', $user->companies()->pluck('id')->toArray());
    }
}
