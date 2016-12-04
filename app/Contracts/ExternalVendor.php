<?php

namespace App\Contracts;

interface ExternalVendor {

	public function search($query, $location, $provider);

	public function getDetails($id, $provider);
}