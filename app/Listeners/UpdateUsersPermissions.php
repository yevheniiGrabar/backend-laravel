<?php

namespace App\Listeners;

use App\Events\RoleSync;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUsersPermissions implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  RoleSync $event
     *
     * @return void
     */
    public function handle(RoleSync $event): void
    {
        $event->role
            ->users()
            ->get()
            ->each(fn(User $user) => $user->permissions()->toggle($event->permissions));
    }
}
