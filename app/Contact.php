<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
    	'user_id',
    	'first_name',
    	'last_name',
    	'email',
    	'phone',
    	'invitation_sent',
    	'token',
    	'invitation_sent_at'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
