<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalVendorReview extends Model
{
    protected $table = 'external_vendor_reviews';

    protected $fillable = [
		'body',
		'author',
		'url',
		'review_date',
		'rating',
		'external_review_site_id',
		'user_id',
    ];

    protected $dates = [
    	'created_at',
    	'update_at',
    	'review_date'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(ThirdPartyTestimonialSite::class, 'external_review_site_id');
    }


}
