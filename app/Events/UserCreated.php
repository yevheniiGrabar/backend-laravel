<?php

namespace App\Events;

use App\Enums\Users\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param string $role
     */
    public function __construct(
        public User $user,
        public string $role = RolesEnum::CLIENT
    ) {
    }
}
