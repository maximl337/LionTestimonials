<?php

namespace App\Services;

use Mail;
use App\User;
Use Carbon\Carbon;

class UserVerificationService {

	public function send(User $user)
	{
		// make token
		$token = md5(str_random(16));

		// make url
		$url = env('APP_URL') . 'user/verify/' . $token;
		
		// update user
		$user->update([
				'verification_token' => $token,
				'verification_sent_at' => Carbon::now()
			]);

		
		// send email
		Mail::send('emails.verify', ['user' => $user, 'url' => $url], function ($m) use ($user) {

            $m->to($user->email, $user->first_name)->subject('Account verification');
        });
	}
}