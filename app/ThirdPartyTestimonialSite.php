<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyTestimonialSite extends Model
{
    protected $table = 'third_party_testimonial_sites';

    protected $fillable = [
    	'provider',
    	'business_url',
        'business_name',
        'business_id',
    	'user_id'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function externalReviews()
    {
        return $this->hasMany(ExternalVendorReview::class, 'external_review_site_id', 'id');
    }
}
