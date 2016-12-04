<?php

namespace App\Contracts;

use App\User;

interface TestimonialInterface {

	public function getAverageRating(User $user);

	public function getReviewCountByProvider(User $user);

}