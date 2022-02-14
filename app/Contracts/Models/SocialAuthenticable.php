<?php

namespace App\Contracts\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface SocialAuthenticable
{
	/**
	 * Implementing model must have method to fid user
	 * based on social vendor and token
	 * 
	 * @param  string  $vendor
	 * @param  string  $token
	 * @param  bool    $abortIfFail
	 * @return self
	 */
	public static function findBySocial(
		string $vendor, 
		string $token, 
		bool $abortIfFail
	): self;

	/**
	 * Implement model relationship with the socialite user
	 * 
	 * @return Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function socialiteUser(): HasOne;
}