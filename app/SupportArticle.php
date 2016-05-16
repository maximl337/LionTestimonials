<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportArticle extends Model
{
    protected $fillable = [
    	'title',
    	'body',
    	'tags'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function isOwner($user_id)
    {
    	return $this->user_id == $user_id ? true : false;
    }
}
