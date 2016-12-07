<?php

namespace App\Contracts;

interface MicrosoftApi {

	public function getOAuthUrl();

	public function getAccessToken($authorization_code);

	public function getUserContacts($access_token);
	
}