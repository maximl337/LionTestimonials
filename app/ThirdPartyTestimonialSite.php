<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyTestimonialSite extends Model
{
    protected $table = 'third_party_testimonial_sites';

    protected $fillable = [
    	'provider',
    	'url',
    	'user_id'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
