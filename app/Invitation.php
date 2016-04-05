<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
	protected $table = 'invitations';

	protected $fillable = [
		'contact_id',
		'email',
		'sms',
		'token',
		'custom_email'
	];

	/**
	 * [contact description]
	 * @return [type] [description]
	 */
	public function contact()
	{
		return $this->belongsTo('App\Contact');
	}
}
