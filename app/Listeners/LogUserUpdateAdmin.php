<?php

namespace App\Listeners;

use App\Events\UserUpdateAdmin;
use App\Models\UserLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserUpdateAdmin
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
    public function handle(UserUpdateAdmin $event): void
    {
        $user = $event->user;

        UserLog::create([
            'user_id' => $user->id,
            'action'  => $user->id . ' user is admin',
        ]);
    }
}
