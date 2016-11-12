<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branding extends Model
{
    protected $table = 'branding';

    protected $fillable = [
    	'primary_color',
        'background_color',
    	'text_color',
    	'user_id'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
