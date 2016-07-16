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

			$grabzit_endpoint = "http://api.grabz.it/services/getfile.ashx?id=";

			$gif_url = $grabzit_endpoint . $id;

			$imgur_gif_link = (new ImageService)->upload($gif_url);

			Log::info("grabzit", ["imgur" => $imgur_gif_link]);

		} catch (Exception $e) {
			throw $e;
		}
		
	}
}