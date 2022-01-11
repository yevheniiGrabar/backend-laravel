<?php

namespace App\Events;

use App\Models\Role;
use Illuminate\Queue\SerializesModels;

class RoleSync
{
    use SerializesModels;

    /**
     * RoleDetached constructor.
     *
     * @param Role  $role
     * @param array $permissions
     */
    public function __construct(
        public Role $role,
        public array $permissions
    ) {
    }
}
