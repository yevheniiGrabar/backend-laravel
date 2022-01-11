<?php

namespace App\Models;

use App\Enums\Users\RolesEnum;
use App\Events\UserCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @package App\Models
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $fullname
 * @property string $email
 * @property string $password
 * @property string $country
 * @property integer $phone
 * @property string $city
 * @property string $position
 * @property int $affiliate_id
 * @property string $status
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $table = 'users';

    /**
     * @var string[]
     */
    protected $dispatchesEvents = [
        'created' => UserCreated::class, // @TODO: In future we need to process user role
    ];

    protected function getDefaultGuardName(): string
    {
        return 'api';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'position',
        'country',
        'city',
        'avatar',
        'status',
        'affiliate_id',
        'medialinks'
    ];

    protected $appends = [
        'fullname',
        'avatar_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'medialinks' => 'json'
    ];

    public static function booted()
    {
        static::saving(function (User $user) {
            if ($user->getOriginal('password') !== $user->getAttribute('password')) {
                $user->password = Hash::make($user->password);
            }
        });
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'user_company');
    }

    public function isModerator()
    {
        /**
         * TODO: implement this;
         */

        return true;
    }

    public function resetPasswordToken()
    {
        return $this->hasOne(PasswordResetToken::class);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? Storage::disk('public')->url($this->table . "/" . last(explode("/", $this->avatar)))
            : null;
    }

    public function scopeViewable(Builder $builder)
    {
        /** @var self $user */
        $user = Auth::user();

        if ($user->hasRole(RolesEnum::SUPER_ADMIN)) {
            return $builder;
        }

        return $builder->whereHas('companies', function (Builder $query) use ($user) {
            $query->whereIn('id', $user->companies()->pluck('id')->toArray())
                ->orWhere('owner_id', $user->id);
        });
    }

    /**
     * @return string
     */
    public function getFullnameAttribute(): string
    {
        return trim(sprintf('%s %s', $this->first_name, $this->last_name));
    }
}
