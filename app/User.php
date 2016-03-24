<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    
        'first_name', 
        'last_name', 
        // 'email',
        // 'password',
        'picture',
        'business_name',
        'business_logo',
        'bio',
        'street',
        'city',
        'country',
        'state',
        'zip',
        'verification_token',
        'verification_sent_at',
        'verified_at'
        
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAddress()
    {
        return $this->street . ' ' .
                $this->city . ' ' .
                $this->state . ' ' .
                $this->zip . ' ' .
                $this->country;
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    public function testimonials()
    {
        return $this->hasMany('App\Testimonial');
    }
}
