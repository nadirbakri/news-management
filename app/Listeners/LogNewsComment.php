<?php

namespace App\Listeners;

use App\Events\NewsComment;
use App\Models\NewsLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogNewsComment
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
    public function handle(NewsComment $event): void
    {
        $news = $event->news;
        $user = $event->user;

        NewsLog::create([
            'news_id' => $news->id,
            'action'  => $user . ' leave a comment at ' . $news->id,
        ]);
    }
}
