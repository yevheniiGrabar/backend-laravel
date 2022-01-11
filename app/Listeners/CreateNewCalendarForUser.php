<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\User;
use App\Services\CalendarService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateNewCalendarForUser implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event): void
    {
        $payload = [
            'owner_type' => User::class,
            'owner_id' => $event->user->id,
            'summary' => sprintf('%s календарь', $event->user->fullname)
        ];

        (new CalendarService())->store($payload);
    }
}
