<?php

namespace App\Services;

use App\Contracts\VideoToGif;
use Log;
use Auth;
use App\Video;
use Storage;
use App\Services\ImageService;
use App\Clients\GrabzIt\GrabzItClient;

class GrabzitService implements VideoToGif {

	protected $client;

	public function __construct(GrabzItClient $client)
	{
		$this->client = $client;
	}

	public function convert($video_url, $custom_id)
	{
		try {
			
			$this->client->SetAnimationOptions($video_url, $custom_id, null, null, null, 10);

			$this->client->Save(url("/grabzit"));

		} catch (Exception $e) {
			
			throw $e;
		
		}
		
	}

	public function save(array $input)
	{

		try {

			$message = $input["message"];

			$customId = $input["customid"];

			$id = $input["id"];

			$filename = $input["filename"];

			$format = $input["format"];

			$result = $this->client->GetResult($id);

			$destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/images/' . $filename;

			Log::info("grabzit", ["storage path" => $destinationPath]);

			file_put_contents($destinationPath, $result);

			$storage_path = 'sellwithreviews.com/'.env('APP_ENV').'/gifs/' . $filename;

            Storage::put(
                        $storage_path,
                        file_get_contents($destinationPath)
                    );

            $video = Video::findOrFail($customId);

            $video->update(['gif_path' => $storage_path]);
			
		} catch (Exception $e) {
			throw $e;
		}
		
	}
}