<?php

namespace App\Events;

use App\Events\Event;
use App\ThirdPartyTestimonialSite;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ExternalVendorStored extends Event
{
    use SerializesModels;

    public $thirdPartyTestimonialSite;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ThirdPartyTestimonialSite $thirdPartyTestimonialSite)
    {
        $this->thirdPartyTestimonialSite = $thirdPartyTestimonialSite;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
