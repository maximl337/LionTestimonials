<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactImport extends Model
{
    protected $table = 'contact_imports';

    protected $fillable = [
    	'user_id',
    	'provider',
    	'email',
    	'first_name',
    	'last_name',
    	'phone'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
