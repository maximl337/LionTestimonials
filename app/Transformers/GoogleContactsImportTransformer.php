<?php

namespace App\Transformers;

use Auth;

class GoogleContactsImportTransformer extends Transformer {

	public function transform($object) {

		try {

			//return $object;

			if(empty($object['gd$email'][0]['address']) ||
				empty($object['title']['$t'])) {
				return [];
			}

			$name = [];

			$name = explode(" ", $object['title']['$t']);

			return [
				'first_name' => $name[0],
				'last_name' => !empty($name[1]) ? $name[1] : $name[0],
				'email' => $object['gd$email'][0]['address'],
				'phone' => !empty($object['gd$phoneNumber'][0]['$t']) ? $object['gd$phoneNumber'][0]['$t'] : ""
			];


		} catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

			throw $e;

		} catch(\Exception $e) {

			throw $e;
		}
		
		

	}
}