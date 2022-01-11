<?php

namespace App\Listeners;

use App\Events\ResetPasswordEvent;
use App\Notifications\ResetPasswordNotification;

class ResetPasswordListener
{
    public function handle(ResetPasswordEvent $event)
    {
        $event->user->notify(new ResetPasswordNotification($event->user->resetPasswordToken->change_password_token));
    }
}
