<?php

namespace App\Listeners;

use App\Events\NewsDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\NewsLog;

class LogNewsDeleted
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
    public function handle(NewsDeleted $event): void
    {
        $news = $event->news;

        NewsLog::create([
            'news_id' => $news->id,
            'action'  => $news->id . ' news deleted',
        ]);
    }
}
