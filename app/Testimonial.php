<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
    	'contact_id',
    	'user_id',
    	'rating',
        'email',
    	'body',
    	'token',
        'thumbnail',
        'url',
        'approved_at',
        'seen_at',
    ];

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    public function scopeUnapproved($query)
    {
        return $query->whereNull('approved_at');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
}
