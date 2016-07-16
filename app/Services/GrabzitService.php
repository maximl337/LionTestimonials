<?php

namespace App\Services;

use App\Contracts\VideoToGif;
use Auth;
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
			
			$customid = uniqid() . "-" . Auth::id();

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

			$result = $this->client->GetResult($id);

			$destinationPath = public_path('images/videogifs');

			file_put_contents($destinationPath . $filename, $result);

		} catch (Exception $e) {
			throw $e;
		}
		
	}
}