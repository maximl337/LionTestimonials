<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalVendorSite extends Model
{
    protected $table = 'external_vendor_sites';

    protected $fillable = [
    	'access_token',
        'token_type',
        'expires_at',
        'provider',
    ];

    protected $dates = [
    		'created_at',
    		'updated_at',
    		'expires_at'
    ];

    
}
