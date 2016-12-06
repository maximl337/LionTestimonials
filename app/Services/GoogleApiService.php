<?php 

namespace App\Services;


use GuzzleHttp\Client;
use App\Contracts\GoogleApi;


class GoogleApiService implements GoogleApi {

	protected $client;

	public function __construct(Client $client)
	{
		$this->client = $client;	
	}

	/**
	 * [getOAuthUrl description]
	 * @return [type] [description]
	 */
	public function getOAuthUrl() {

		$base_url = 'https://accounts.google.com/o/oauth2/v2/auth?';

		$params = [
			'redirect_uri' 	=> url('/oauth2/google/callback'),
			'prompt' 		=> 'consent',
			'response_type'	=> 'code',
			'client_id'		=> env('GOOGLE_OAUTH_CLIENT_ID'),
			'scope' 		=> 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/contacts',
			'access_type' 	=> 'offline'

		];

		return $base_url . http_build_query($params);

	}

	public function getAccessToken($authorization_code) {

		try {

			$url = 'https://www.googleapis.com/oauth2/v4/token';

			$params = [
				'code' => $authorization_code,
				'redirect_uri' => url('/oauth2/google/callback'),
				'client_id'		=> env('GOOGLE_OAUTH_CLIENT_ID'),
				'client_secret'		=> env('GOOGLE_OAUTH_CLIENT_SECRET'),
				'grant_type' => 'authorization_code'
			];

			$resp = $this->client->request('POST', $url, [
					'form_params' => $params
				]);

			$result = json_decode($resp->getBody());

			return $result->access_token;

		} catch (GuzzleException $e) {

			throw $e;
			
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function getUserEmail($access_token) {

		try {

			$url = 'https://www.googleapis.com/oauth2/v2/userinfo';

			$resp = $this->client->request('GET', $url, [ 

					'headers' => [
						'Authorization' => 'Bearer ' . $access_token
					]

				]);

			$result = json_decode($resp->getBody());

			return $result->email;

		} catch (GuzzleException $e) {

			throw $e;
			
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function getUserContacts($access_token, $email) {

		try {

			$url = 'https://www.google.com/m8/feeds/contacts/'.$email.'/full';

			$resp = $this->client->request('GET', $url, [ 

					'headers' => [
						'Authorization' => 'Bearer ' . $access_token
					],

					'query' => [
						'alt' => 'json',
						'max-results' => '50000'
					]
				]);

			$result = $resp->getBody();

			return json_decode($result, true);

		} catch (GuzzleException $e) {

			throw $e;

		} catch (Exception $e) {
			throw $e;
		}
	}

}