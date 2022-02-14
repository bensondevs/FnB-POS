<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
	/**
	 * Twilio REST client class container
	 * 
	 * @var \Twilio\Rest\Client|null
	 */
	protected $client;

	/**
	 * Service constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		// Acquire some information from config
		$sid = config('twilio.sid');
		$authToken = config('twilio.auth_token');

		// Make the REST Client instance
		$this->client = new Client($sid, $authToken);
	}

	/**
	 * Send message to receiver through twilio service
	 * 
	 * @param  string  $receiver
	 * @param  array   $content
	 * @return stdObj
	 */
	public function sendMessage(string $receiver, array $content)
	{
		return $this->client->messages->create($receiver, $content);
	}
}