<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    /** @var string $guard_name */
    public $guard_name = 'api';

    /** @var string[] $hidden */
    protected $hidden = ['pivot'];
}
