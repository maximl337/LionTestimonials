<?php

namespace App\Transformers;

use Auth;

class OutlookContactsImportTransformer extends Transformer {

	public function transform($object) {

		try {

			//return $object;

			if(empty($object['EmailAddresses']) ||
				empty($object['GivenName'])) {
				return [];
			}

			return [
				'first_name' => $object['GivenName'],
				'last_name' => !empty($object['Surname']) ? $object['Surname'] : $object['GivenName'],
				'email' => $object['EmailAddresses'][0]['Address'],
				'phone' => !empty($object['MobilePhone1']) ? $object['MobilePhone1'] : ""
			];


		} catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

			throw $e;

		} catch(\Exception $e) {

			throw $e;
		}
		
		

	}
}