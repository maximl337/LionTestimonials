<?php

namespace App\Services;

use Imgur;
use Log;

class ImageService 
{

    
    /**
     * upload given image to imgur
     * @param  filepath $imagePath path to image 
     * @param str $type type of image to upload
     * @return str            imgur url of image
     */
    public function upload($imagePath, $type = 'file')
    {
        $imageData = array(
            'image' => $imagePath,
            'type'  => $type
        );

        $basic = Imgur::api('image')->upload($imageData);

        //parse response
        $resp = $basic->getData();

        //Log::info('imgur response', $resp);

        if(empty($resp['link'])) return '';

        return $resp['link'];
    }

}