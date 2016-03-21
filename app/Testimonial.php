<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
    	'contact_id',
    	'user_id',
    	'rating',
    	'body',
    	'video'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    
}
