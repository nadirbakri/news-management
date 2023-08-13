<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Models\UserLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserLogin
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
    public function handle(UserLogin $event): void
    {
        $user = $event->user;

        UserLog::create([
            'user_id' => $user->id,
            'action'  => $user->id . ' user login',
        ]);
    }
}
