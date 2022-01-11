<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'user_id',
        'change_password_token',
        'active',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setChangePasswordTokenAttribute($value)
    {
        $this->attributes['change_password_token'] = Hash::make($value);
    }

    public function scopeActive(Builder $query)
    {
        return $query->whereDate('expired_at', '<', Carbon::now()->toDateTimeString());
    }
}
