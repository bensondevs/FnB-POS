<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GuzzleClientService
{
	/**
	 * Guzzle client class container
	 * 
	 * @var \GuzzleHttp\Client|null
	 */
	private $client;

	/**
	 * Service class constructor method
	 * 
	 * @param  array  $headers
	 * @return void
	 */
	public function __construct(array $headers = [])
	{
		$this->http = empty($headers) ? 
			new Client : 
			new Client(['headers' => $headers]);
	}

	/**
	 * Transform response using json_decode
	 * 
	 * @param  string  $response
	 * @return array
	 */
	public function transformResponse($response, bool $toArray = false)
	{
		$body = $response->getBody();
		return json_decode($body, $toArray);
	}

	/**
	 * Set headers of the client class
	 * 
	 * @param  array  $headers
	 * @return $this
	 */
	public function setHeaders(array $headers)
	{
		$this->client = new Client(['headers' => $headers]);

		return $this;
	}

	/**
	 * Make get request
	 * 
	 * @param  string  $url
	 * @param  array   $parameters
	 * @param  array   $options
	 * @return array
	 */
	public function get(string $url, array $parameters, array $options = [])
	{
		// Set ? mark as the start of the parameters
		if (last_character($url) !== '?') {
			$url .= '?';
		}

		// Fetch parameters to url
		foreach ($parameters as $key => $value) {
			$url .= $key . '=' . $value;

			// Add & if not the last parameter
			if (array_key_last($parameters) !== $key) {
				$url .= '&';
			}
		}

		$response = $this->client->request('GET', $url, $options);
		return $this->transformResponse($response);
	}

	/**
	 * Make post request
	 * 
	 * @param  string  $url
	 * @param  array   $payload
	 * @return array
	 */
	public function post(string $url, array $payload)
	{
		$response = $this->client->request('POST', $url, $payload);
		return $this->transformResponse($response);
	}

	/**
	 * Make put request
	 * 
	 * @param  string  $url
	 * @param  array   $payload
	 */
	public function put(string $url, array $payload)
	{
		$response = $this->client->request('PUT', $url, $payload);
		return $this->transformResponse($response);
	}

	/**
	 * Make patch request
	 * 
	 * @param  string  $url
	 * @param  array   $payload
	 */
	public function patch(string $url, array $payload)
	{
		$response = $this->client->request('PATCH', $url, $payload);
		return $this->transformResponse($response);
	}

	/**
	 * Make delete request
	 * 
	 * @param  string  $url
	 * @param  array   $payload
	 */
	public function delete(string $url, array $payload = [])
	{
		$response = empty($payload) ?
			$this->client->request('DELETE', $url) :
			$this->client->request('DELETE', $url, $payload);

		return $this->transformResponse($response);
	}
}