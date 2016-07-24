<?php

namespace App\Listeners;

use App\Events\VideoStoredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VideoStoredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VideoStoredEvent  $event
     * @return void
     */
    public function handle(VideoStoredEvent $event)
    {
        //
    }
}
