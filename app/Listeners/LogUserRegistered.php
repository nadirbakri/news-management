<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\UserLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserRegistered
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
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        UserLog::create([
            'user_id' => $user->id,
            'action'  => 'new user registered',
        ]);
    }
}
