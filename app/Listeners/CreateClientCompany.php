<?php

namespace App\Listeners;

use App\Enums\Users\RolesEnum;
use App\Events\UserCreated;

class CreateClientCompany
{
    public const DEFAULT_CLIENT_COMPANY_NAME = "User #:id Company";

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        if (!$event->user->hasRole(RolesEnum::CLIENT)) {
            return;
        }

        $event->user->companies()->create([
            'title' => __(self::DEFAULT_CLIENT_COMPANY_NAME, ['id' => $event->user->id]),
            'owner_id' => $event->user->id,
        ]);
    }
}
