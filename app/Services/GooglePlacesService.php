<?php

namespace App\Services;

use Log;
use Auth;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Contracts\GooglePlaces;
use App\ExternalVendorSite;
use GuzzleHttp\Exception\GuzzleException;

class GooglePlacesService implements GooglePlaces {
	
	protected $client;

	function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * [search description]
	 * @param  [type] $query    [description]
	 * @param  [type] $location [description]
	 * @param  [type] $provider [description]
	 * @return [type]           [description]
	 */
	public function search($term, $location)
	{
		try {

			$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

			$results = $this->client->request('GET', $url, [ 

					'query' => [
						'key' => env('GOOGLE_API_KEY'),
						'query' => $term . " " . $location
					]
				]);

			return json_decode($results->getBody(), true);
			
		} catch (GuzzleException $e) {

			throw $e;

		} catch (Exception $e) {
			
			throw $e;
		
		}
		
	}

	/**
	 * [getDetails description]
	 * @param  [type] $place_id [description]
	 * @return [type]           [description]
	 */
	public function getDetails($place_id)
	{
		try {

			$url = 'https://maps.googleapis.com/maps/api/place/details/json';

			$results = $this->client->request('GET', $url, [ 
					'query' => [
						'key' => env('GOOGLE_API_KEY'),
						'placeid' => $place_id
					]
				]);

			return json_decode($results->getBody(), true);
			
		} catch (GuzzleException $e) {

			throw $e;

		} catch (Exception $e) {
			
			throw $e;
		
		}
	}

}