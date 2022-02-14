<?php

namespace App\Repositories;

use App\Models\{ User, SocialiteUser };
use App\Services\SocialiteService;

class AuthRepository
{
	/**
	 * Repository class constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Execute login by supplied input of identity and password
	 * 
	 * @param  array<string, string>  $loginData
	 * @return \App\Models\User|null
	 */
	public function login(array $loginData)
	{
		$identity = $loginData['identity']; // Either email or username
		if (! $user = User::findByIdentity($identity)) {
			return abort(404, 'No user found in record that has this email or username');
		}

		$password = $loginData['password'];
		if (! $user->isPasswordMatch($password)) {
			return abort(401, 'Password mismatch the record.');
		}

		// Authenticate user
		return $user->authenticate();
	}

	/**
	 * Socialite user login handler
	 * 
	 * @param  \stdClass  $providerUser
	 * @param  string     $provider
	 * @return \App\Models\User
	 */
	public function socialiteLogin(stdClass $providerUser, string $provider = 'google')
	{
		$token = $providerUser->token;
		if (! $socialiteUser = SocialiteUser::findByToken($provider, $token)) {
			//
		}

		// Record it in the socialite user instance
		$socialiteUser->user_instance = $providerUser;
		$socialiteUser->save();

		if (! $user = $socialiteUser->user) {
			//
		}

		return $user->authenticate();
	}
}