<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
    	'token',
    	'user_id',
    	'title',
    	'thumbnail',
        'url',
        'profile_video',
        'gif_path'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

}
