<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
    	'name',
    	'user_id'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function testimonials()
    {
    	return $this->belongsToMany(Testimonial::class);
    }
}
