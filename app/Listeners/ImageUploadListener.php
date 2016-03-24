<?php

namespace App\Listeners;

use Log;
use File;
use App\Events\ImageUpload;
use App\Services\ImageService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImageUploadListener implements ShouldQueue
{

    protected $imageService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ImageService $service)
    {
        $this->imageService = $service;
    }

    /**
     * Handle the event.
     *
     * @param  ImageUpload  $event
     * @return void
     */
    public function handle(ImageUpload $event)
    {
        $data = $event->data;

        $user = $data['user'];

        $file = $data['file'];

        $file_path = $data['file_path'];

        $type = $data['type'];

        // upload file
        $url = $this->imageService->upload($file);

        if(!is_null($url)) {

            Log::info('image uploaded', [$url]);

            if($type == 'picture') {
                Log::info('attempting to update picture');
                $user->update(['picture' => $url]);
            } else {
                Log::info('attempting to update logo');
                $user->update(['business_logo' => $url]);
            }

            //$user->save();

            //File::delete($file);

        } // url not empty

    }
}
