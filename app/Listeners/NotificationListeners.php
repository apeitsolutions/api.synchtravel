<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\b2cnotification;
use App\Models\Booking;
use Illuminate\Support\Facades\Notification;

class NotificationListeners
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $admins = \DB::table('dow_b2c_laravel.bookings')->whereHas('roles', function ($query) {
            $query->where('id', 1);
        })->get();

        Notification::send($admins, new b2cnotification($event->user));
    }
}
