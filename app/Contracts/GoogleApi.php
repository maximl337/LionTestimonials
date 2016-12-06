<?php

namespace App\Contracts;

interface GoogleApi {

	public function getOAuthUrl();

	public function getAccessToken($authorization_code);

	public function getUserEmail($access_token);

	public function getUserContacts($access_token, $email);
	
}