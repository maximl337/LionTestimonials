<?php 

namespace App\Services;


use GuzzleHttp\Client;
use App\Contracts\MicrosoftApi;


class MicrosoftApiService implements MicrosoftApi {

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

		$base_url = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?';

		$params = [
			'redirect_uri' 	=> url('/oauth2/outlook/callback'),
			'response_type'	=> 'code',
			'client_id'		=> env('MICROSOFT_API_CLIENT_ID'),
			'scope' 		=> 'https://outlook.office.com/contacts.read'

		];

		return $base_url . http_build_query($params);

	}

	public function getAccessToken($authorization_code) {

		try {

			$url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';

			$params = [
				'code' => $authorization_code,
				'client_id'		=> env('MICROSOFT_API_CLIENT_ID'),
				'client_secret'		=> env('MICROSOFT_API_CLIENT_SECRET'),
				'redirect_uri' 	=> url('/oauth2/outlook/callback'),
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

	public function getUserContacts($access_token) {

		try {

			$url = 'https://outlook.office.com/api/v2.0/me/contacts';

			$resp = $this->client->request('GET', $url, [ 

					'headers' => [
						'Authorization' => 'Bearer ' . $access_token
					],

					'query' => [
						'$select' => 'EmailAddresses,GivenName,Surname,HomePhones,MobilePhone1',
						'$top' => '50000'
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