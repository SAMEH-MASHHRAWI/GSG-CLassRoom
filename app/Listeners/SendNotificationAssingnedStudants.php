<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\ClassworkCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NewClassworkNotification;
use Illuminate\Support\Facades\Notification;

class SendNotificationAssingnedStudants
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClassworkCreated $event): void
    {
        // $user=User::find(1);
        // $user->notify(new NewClassworkNotification($event->classwork));
        Notification::send($event->classwork->users,new NewClassworkNotification($event->classwork));
    }
}
