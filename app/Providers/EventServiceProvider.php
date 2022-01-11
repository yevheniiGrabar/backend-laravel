<?php

namespace App\Providers;

use App\Events\ResetPasswordEvent;
use App\Events\RoleSync;
use App\Events\UpdateCalendarEventSubs;
use App\Events\UserCreated;
use App\Listeners\CreateClientCompany;
use App\Listeners\CreateNewCalendarForUser;
use App\Listeners\ResetPasswordListener;
use App\Listeners\UpdateDataSubs;
use App\Listeners\UpdateUsersPermissions;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ResetPasswordEvent::class => [
            ResetPasswordListener::class
        ],
        UserCreated::class => [
            CreateClientCompany::class,
            CreateNewCalendarForUser::class
        ],
        UpdateCalendarEventSubs::class => [
            UpdateDataSubs::class
        ],
        RoleSync::class => [
            UpdateUsersPermissions::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
