<?php

namespace App\Transformers;

use Carbon\Carbon;

class YelpDetailsTransformer extends Transformer {

	public function transform($object) {

		try {

			return [
			
				'body'						=> $object['text'],
				'author'					=> $object['user']['name'],
				'url'						=> $object['url'],
				'review_date'				=> $object['time_created'],
				'rating'					=> $object['rating'],
				'external_review_site_id'	=> $object['external_review_site_id']
			];


		} catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

			throw $e;

		} catch(\Exception $e) {

			throw $e;
		}

	}
}