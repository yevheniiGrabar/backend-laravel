<?php

namespace App\Models;

use App\Enums\Users\RolesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'original',
        'company_id',
        'owner_id'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
