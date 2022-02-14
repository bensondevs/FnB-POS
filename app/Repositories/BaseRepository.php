<?php

namespace App\Repositories;

class BaseRepository
{
	/**
	 * Status of repository execution
	 * 
	 * @var string
	 */
	private $_status = 'error';

	/**
	 * Collection of statuses in repository
	 * 
	 * @var array<int, string>
	 */
	private $_statuses = [];

	/**
	 * HTTP Response for repository execution
	 * 
	 * @var int
	 */
	private $_httpResponse = 0;

	/**
	 * Message of repository execution
	 * 
	 * @var string
	 */
	private $_message = 'Unknown error occured.';

	/**
	 * Messages of repository execution
	 * 
	 * @var array<int, string>
	 */
	private $_messages = [];

	/**
	 * Set execution status of the repository
	 * 
	 * @param  string  $status
	 * @return $this
	 */
	public function setStatus(string $status = 'success')
	{
		$this->_status = $status;
		$this->_statuses[] = $status;
	}

	/**
	 * Get execution status of the repository
	 * 
	 * This will return string if the status is only one,
	 * otherwise, this will return array instead.
	 * 
	 * @return string|array
	 */
	public function getStatus()
	{
		return count($this->_statuses) ? 
			$this->_statuses : 
			$this->_status;
	}

	/**
	 * Set executon message of the repository
	 * 
	 * @param  string  $message
	 * @return $this
	 */
	public function setMessage(string $message = 'Success')
	{
		$this->_message = $message;
		$this->_messages[] = $message;
	}

	/**
	 * Get execution message of the repository
	 * 
	 * This will return string if the message is only one,
	 * otherwise, this will return array instead.
	 * 
	 * @return string|array
	 */
	public function getMessage()
	{
		return count($this->_messages) ? 
			$this->_messages : 
			$this->_message;
	}

	/**
	 * Set repository method execution as success
	 * 
	 * @param  string  $message
	 * @return void
	 */
	public function setSuccess(string $message = 'Success')
	{
		$this->_status = 'success';
		$this->_message = $message;
	}
}