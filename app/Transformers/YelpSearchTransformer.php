<?php

namespace App\Transformers;

use Auth;

class YelpSearchTransformer extends Transformer {

	public function transform($object) {

		try {

			// format address
			$address = "";

			if(!empty($object['location']['address1'])) {
				$address .= ", " . $object['location']['address1'];
			}

			if(!empty($object['location']['city'])) {
				$address .= ", " . $object['location']['city'];
			}

			if(!empty($object['location']['state'])) {
				$address .= ", " . $object['location']['state'];
			}

			if(!empty($object['location']['zip_code'])) {
				$address .= ", " . $object['location']['zip_code'];
			}

			if(!empty($object['location']['country'])) {
				$address .= ", " . $object['location']['country'];
			}

			return [
				'id' => $object['id'],
				'image_url' => $object['image_url'],
				'name' => $object['name'],
				'rating' => $object['rating'],
				'review_count' => $object['review_count'],
				'url' => $object['url'],
				'address' => $object['location']['address1'] . " " . $object['location']['city'] . " " . $object['location']['state'],
			];


		} catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

			throw $e;

		} catch(\Exception $e) {

			throw $e;
		}
		
		

	}
}