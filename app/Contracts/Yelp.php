<?php

namespace App\Contracts;

interface Yelp {

	public function search($term, $location);

	public function getDetails($id);
}