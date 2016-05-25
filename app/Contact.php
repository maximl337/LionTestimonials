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
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function invitation()
    {
        return $this->hasMany('App\Invitation');
    }

    public function getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

}
