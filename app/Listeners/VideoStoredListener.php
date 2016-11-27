<?php

namespace App\Listeners;

use Log;
use App\Video;
use App\Contracts\VideoToGif;
use App\Events\VideoStoredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VideoStoredListener implements ShouldQueue
{

    protected $videoToGif;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(VideoToGif $videoToGif)
    {
        $this->videoToGif = $videoToGif;
    }

    /**
     * Handle the event.
     *
     * @param  VideoStoredEvent  $event
     * @return void
     */
    public function handle(VideoStoredEvent $event)
    {
        try {

            $video = $event->video;

            if($video->url) {
                if(env('APP_ENV') == 'production') {
                    $this->videoToGif->convert($video->url, $video->id);    
                }
            }
            
        } catch (Exception $e) {
            
            Log::error("Video to Gif", [$e->getMessage()]);
        }
        
        
    }
}
