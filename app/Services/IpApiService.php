<?php

namespace App\Services;

class IpApiService
{
	/**
	 * Guzzle client service class container
	 * 
	 * @var \App\Services\GuzzleClientService
	 */
	private $apiService;

	/**
	 * Base API URL of the IP Service
	 * 
	 * @var string
	 */
	protected $baseApiUrl = 'http://ip-api.com/json/';

	/**
	 * IP Address of the current user
	 * 
	 * @var string
	 */
	protected $ipAddress;

	/**
	 * List of fields returned in API request
	 * to IP API
	 * 
	 * @var array
	 */
	private $fields = [
		'status' => false,
		'message' => false,
		'continent' => false,
		'continentCode' => false,
		'country' => false,
		'countryCode' => false,
		'region' => false,
		'regionName' => false,
		'city' => false,
		'zip' => false,
		'lat' => false,
		'lon' => false,
		'timezone' => false,
		'offset' => false,
		'currency' => false,
		'isp' => false,
		'org' => false,
		'as' => false,
		'asname' => false,
		'reverse' => false,
		'mobile' => false,
		'proxy' => false,
		'hosting' => false,
		'query' => false,
	];

	/**
	 * Service constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->apiService = new GuzzleClientService();
		$this->ipAddress = request()->ip() ?: '24.48.0.1';
	}

	/**
	 * Set which fields are going to be returned
	 * 
	 * @param  array  $fieldAttributes
	 * @return $this
	 */
	public function setReturnedFields(array $fieldAttributes)
	{
		// Restart the all fields array
		foreach ($this->fields as $attribute => $fields) {
			$this->fields[$attribute] = false;
		}

		// Set all returned fields to true if supplied in attributes
		foreach ($fieldAttributes as $attribute) {
			if (isset($this->fields[$attribute])) {
				$this->fields[$attribute] = true;
			}
		}

		return $this;
	}

	/**
	 * Get which fields are going to be returned
	 * 
	 * @return array
	 */
	public function getReturnedFields()
	{
		return array_map(function ($value, $attribute) {
			if ($value) {
				return $attribute;
			}
		}, $this->fields);
	}

	/**
	 * Make get request to IP API
	 * 
	 * @return stdClass
	 */
	public function get()
	{
		$url = concat_paths([
			$this->baseApiUrl, 
			$this->ipAddress
		], false, true);
		$parameters = $this->getReturnedFields();

		return $this->apiService->get($url, $parameters);
	}

	/**
	 * Get currency base on user's IP address
	 * 
	 * @return  string
	 */
	public function getCurrency()
	{
		$this->setReturnedFields(['currency']);
		$response = $this->get();

		return isset($response->currency) ? 
			$response->currency : 'USD';
	}
}