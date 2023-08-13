<?php

namespace App\Listeners;

use App\Events\NewsCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\NewsLog;

class LogNewsCreated
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
    public function handle(NewsCreated $event)
    {
        $news = $event->news;

        NewsLog::create([
            'news_id' => $news->id,
            'action'  => 'new news created',
        ]);
    }
}
