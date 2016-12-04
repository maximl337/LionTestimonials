<?php

namespace App\Transformers;

use Auth;

class GooglePlacesSearchTransformer extends Transformer {

	public function transform($object) {

		try {

			return [
				'id' => $object['place_id'],
				'image_url' => "",
				'name' => $object['name'],
				'rating' => !empty($object['rating']) ? $object['rating'] : " - Not given -",
				'review_count' => "- Not given -",
				'url' => "",
				'address' => !empty($object['formatted_address']) ? $object['formatted_address'] : "",
			];


		} catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

			throw $e;

		} catch(\Exception $e) {

			throw $e;
		}
		
		

	}
}