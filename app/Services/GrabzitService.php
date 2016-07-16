<?php

namespace App\Services;

use App\Contracts\VideoToGif;
use Log;
use Auth;
use Storage;
use App\Services\ImageService;
use App\Clients\GrabzIt\GrabzItClient;

class GrabzitService implements VideoToGif {

	protected $client;

	public function __construct(GrabzItClient $client)
	{
		$this->client = $client;
	}

	public function convert($video_url)
	{
		try {
			
			if(Auth::check()) {
				$customid = uniqid() . "-" . Auth::id();	
			} else {
				$customid = uniqid()."-noid";	
			}
			
			$this->client->SetAnimationOptions($video_url, $customid, null, null, null, 10);

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

			$filename = $input["filename"] . "test test";

			$format = $input["format"];

			//Log::info("grabzit", ["message" => "Image for ID: " . $id]);

			$result = $this->client->GetResult($id);

			$storage_path = 'sellwithreviews.com/'.env('APP_ENV').'/videos/1/' . $fileName;

            Storage::put(
                        $storage_path,
                        file_get_contents($result)
                    );

			Log::info("grabzit", ["aws storage" => $storage_path]);


		} catch (Exception $e) {
			throw $e;
		}
		
	}
}