<?php

namespace App\Transformers;

use Carbon\Carbon;

class GooglePlacesDetailsTransformer extends Transformer {

	public function transform($object) {

		try {

			return [
			
				'body' => $object['text'],
				'author' => $object['author_name'],
				'url' => $object['url'],
				'review_date' => Carbon::createFromTimeStampUTC($object['time'])->toDateTimeString(),
				'rating' => $object['rating'],
				'external_review_site_id' => $object['external_review_site_id']
			];


		} catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

			throw $e;

		} catch(\Exception $e) {

			throw $e;
		}
		
		

	}
}