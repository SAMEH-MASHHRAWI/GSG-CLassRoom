<?php

namespace App\Notifications\Channels;

use App\services\HadaraSms;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;

class HadaraSmsChannel
{
    public function send(object $notifiable,Notification $notification): void
    {
       $services=new HadaraSms(config('services.hadara.key'));
       $services->send(
        $notifiable->routeNotificationforHadara($notification),
        $notification->toHadara($notifiable),
       ); 
    }
}
