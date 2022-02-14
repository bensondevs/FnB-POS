<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;

class SocialiteService
{
	/**
	 * Provider name container
	 * 
	 * @var string|null
	 */
	private $provider = 'google';

	/**
	 * Provider token container
	 * 
	 * @var string|null
	 */
	protected $token;

	/**
	 * Provider user instance container
	 * 
	 * @var stdClass|null
	 */
	protected $providerUser;

	/**
	 * Service constructor method
	 * 
	 * @param  string  $provider
	 * @param  string  $token
	 * @return void
	 */
	public function __construct(string $provider = 'google', string $token = ''): void
	{
		$this->provider = $provider;
		$this->token = $token ?: $this->acquireToken();
	}

	/**
	 * Set the token of the socialite service
	 * 
	 * @param  string  $token
	 * @return $this
	 */
	public function setToken(string $token): self
	{
		$this->token = $token;

		return $this;
	}

	/**
	 * Acquire token from the laravel request instance
	 * 
	 * @return void
	 */
	private function acquireToken()
	{
		$token = request()->input('token', 'UNKNOWN_TOKEN_FOUND_FROM_ANYWHERE');
		
		return $this->token = $token;
	}

	/**
	 * Set the provider of the authentication
	 * 
	 * @param  string  $provider
	 * @return $this
	 */
	public function setProvider(string $provider = 'google'): self
	{
		$this->provider = $provider;

		return $this;
	}

	/**
	 * Acquire the url to provider page
	 * 
	 * @return string
	 */
	public function urlToAuthProvider(): string
	{
		return Socialite::driver($this->provider)
			->stateless()
			->redirect()
			->getTargetUrl();
	}

	/**
	 * Acquire the socialite provider user instance
	 * 
	 * @param  bool   $makeRequest
	 * @return stdClass
	 */
	public function getProviderUser(bool $fromLocal = true): stdClass
	{
		// Ask from local and the provider user is already set
		// then this will just return from the class directly
		if ($fromLocal && (! is_null($this->providerUser))) {
			return $this->providerUser;
		}

		// Acquire the user from the token and
		// when returning it, set it to class attribute simultaneously
		return $this->providerUser = Socialite::driver($this->provider)
			->userFromToken($this->token);
	}
}