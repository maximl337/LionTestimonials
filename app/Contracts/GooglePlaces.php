<?php

namespace App\Contracts;

interface GooglePlaces {

	public function search($term, $location);

	public function getDetails($id);
}