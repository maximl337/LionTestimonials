<?php

namespace App\Services;

use App\Contracts\VideoToGif;
use Log;
use Auth;
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

			$filename = $input["filename"];

			$format = $input["format"];

			//Log::info("grabzit", ["message" => "Image for ID: " . $id]);

			//$result = $this->client->GetResult($id);

			//$destinationPath = storage_path('media/videogifs');

			//file_put_contents($destinationPath . "/" . $filename, $result);

			$link = (new ImageService)->upload("http://api.grabz.it/services/getfile.ashx?id=N2UzOWE1MGQ3ZWEzNDE3NmExMGRhMWQ2NmNiNDliNzQ=-837683d33bbb47dcadf2e7a3189fd5a9d");

			Log::info("grabzit", ["imgur" => $link]);


		} catch (Exception $e) {
			throw $e;
		}
		
	}
}