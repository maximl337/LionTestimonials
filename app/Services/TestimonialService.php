<?php

namespace App\Services;

use Log;
use App\User;
use App\Testimonial;
use App\Contracts\TestimonialInterface;

class TestimonialService implements TestimonialInterface {

    /**
     * [getAverageRating description]
     * @return [type] [description]
     */
	public function getAverageRating(User $user) {

        try {

            // get users testimonials
            $testimonials = $user->testimonials()->pluck('rating')->all();

            // get external vendor reviews
            $external_reviews = $user->externalReviews()->pluck('rating')->all();

            // create collection
            $rating_collection = collect(array_merge($testimonials, $external_reviews));

            return round($rating_collection->avg());
            
        } catch (Exception $e) {
            
            throw $e;

        }
    }

    /**
     * [getReviewsByProvider description]
     * @return [type] [description]
     */
    public function getReviewCountByProvider(User $user) {

        try {

            $payload = [
                'local' => $user->testimonials()->count(),
                'yelp' => $user->thirdPartyTestimonialSites()->where('provider', 'yelp')->first()->externalReviews()->count(),
                'google' => $user->thirdPartyTestimonialSites()->where('provider', 'google')->first()->externalReviews()->count()
            ];

            return $payload;

        } catch (Exception $e) {
            throw $e;            
        }
    }
	
}