<?php

namespace App\Listeners;

use Log;
use App\User;
use App\ExternalVendorReview;
use App\Contracts\ExternalVendor;
use App\ThirdPartyTestimonialSite;
use App\Events\ExternalVendorStored;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Transformers\YelpDetailsTransformer;

class ExternalVendorStoredListener implements ShouldQueue
{

    protected $externalVendorService;

    protected $yelpDetailsTransformer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ExternalVendor $externalVendorService,
                                YelpDetailsTransformer $yelpDetailsTransformer)
    {
        $this->externalVendorService = $externalVendorService;

        $this->yelpDetailsTransformer = $yelpDetailsTransformer;
    }

    /**
     * Handle the event.
     *
     * @param  ExternalVendorStored  $event
     * @return void
     */
    public function handle(ExternalVendorStored $event)
    {
        try {

            $thirdPartySite = $event->thirdPartyTestimonialSite;

            $user = User::findOrFail($thirdPartySite->user_id);

            // get details
            $payload = $this->externalVendorService->getDetails($thirdPartySite->business_id, $thirdPartySite->provider);

            if($thirdPartySite->provider == 'yelp') {

                foreach($payload['reviews'] as $review) {

                    $review['external_review_site_id'] = $thirdPartySite->id;

                    $data = $this->yelpDetailsTransformer->transform($review);

                    $user->externalReviews()->save(new ExternalVendorReview($data));

                }

            }
            
        } catch (Exception $e) {
            Log::error("ExternalVendorStoredListener", [$e->getMessage()]);
        }
    }
}
