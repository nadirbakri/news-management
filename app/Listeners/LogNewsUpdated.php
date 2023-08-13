<?php

namespace App\Listeners;

use App\Events\NewsUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\NewsLog;

class LogNewsUpdated
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
    public function handle(NewsUpdated $event): void
    {
        $news = $event->news;

        NewsLog::create([
            'news_id' => $news->id,
            'action'  => $news->id . ' news updated',
        ]);
    }
}
