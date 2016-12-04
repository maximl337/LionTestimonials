<?php

namespace App\Services;

use Auth;
use GuzzleHttp\Client;
use App\Contracts\Yelp;
use App\Contracts\GooglePlaces;
use App\Contracts\ExternalVendor;
use GuzzleHttp\Exception\GuzzleException;

class ExternalVendorService implements ExternalVendor {

	protected $yelp;

	protected $googlePlaces;

	function __construct(Yelp $yelp, GooglePlaces $googlePlaces) {
		
		$this->yelp = $yelp;

		$this->googlePlaces = $googlePlaces;
	}

	/**
	 * [search description]
	 * @param  [type] $query    [description]
	 * @param  [type] $location [description]
	 * @param  [type] $provider [description]
	 * @return [type]           [description]
	 */
	public function search($query, $location, $provider)
	{
		try {

			$results = ['error' => 'nothing'];

			if($provider == 'yelp') {

				$results = $this->yelp->search($query, $location);

			} else if($provider == 'google') {

				$results = $this->googlePlaces->search($query, $location);

			}

			return $results;

		} catch (GuzzleException $e) {

			throw $e;

		} catch (Exception $e) {
			
			throw $e;
		
		}
		
	}

	public function getDetails($id, $provider)
	{
		try {

			$results = ['error' => 'nothing'];

			if($provider == 'yelp') {

				$results = $this->yelp->getDetails($id);

			} else if($provider == 'google') {

				$results = $this->googlePlaces->getDetails($id);

			}

			return $results;

		} catch (GuzzleException $e) {

			throw $e;

		} catch (Exception $e) {
			
			throw $e;
		
		}
	}

}