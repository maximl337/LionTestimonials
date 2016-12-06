<?php

namespace App\Services;

use Log;
use Auth;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Contracts\Yelp;
use App\ExternalVendorSite;
use GuzzleHttp\Exception\GuzzleException;

class YelpService implements Yelp {
	
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

			// get access token
			$access_token = $this->getAccessToken();

			$url = 'https://api.yelp.com/v3/businesses/search';

			$results = $this->client->request('GET', $url, [ 

					'headers' => [
						'Authorization' => 'Bearer ' . $access_token
					],

					'query' => [
						'location' => $location,
						'term' => $term
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
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getDetails($id)
	{
		try {

			// get access token
			$access_token = $this->getAccessToken();

			$url = 'https://api.yelp.com/v3/businesses/' . $id . '/reviews';

			$results = $this->client->request('GET', $url, [ 

					'headers' => [
						'Authorization' => 'Bearer ' . $access_token
					]

				]);

			$resp = json_decode($results->getBody());

			return $resp->email;
			
		} catch (GuzzleException $e) {

			throw $e;

		} catch (Exception $e) {
			
			throw $e;
		
		}
	}

	/**
	 * [getAccessToken description]
	 * @return [type] [description]
	 */
	public function getAccessToken()
	{
		try {
			
			$record = ExternalVendorSite::where('provider', 'yelp')	
										->where('expires_at', '>', Carbon::now())
										->latest()->first();

			if(!$record) {

				$record = $this->createAccessToken();
			}

			return $record->access_token;

		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * [createAccessToken description]
	 * @return [type] [description]
	 */
	public function createAccessToken()
	{
		try {

			$url = 'https://api.yelp.com/oauth2/token';

			$params = [
				'client_id' => env('YELP_KEY'), 
				'client_secret' => env('YELP_SECRET'),
				'grant_type' => 'client_credentials'
			];

			$resp = $this->client->request('POST', $url, [
					'form_params' => $params
				]);

			$result = json_decode($resp->getBody());

			// create a new record
			$record  = ExternalVendorSite::create([
						'access_token' => $result->access_token,
						'token_type' => $result->token_type,
						'expires_at' => Carbon::now()->addSeconds($result->expires_in),
						'provider' => 'yelp'
					]);

			return $record;
			
		} catch (Exception $e) {
			throw $e;
		}
	}

}