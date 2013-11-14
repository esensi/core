<?php namespace Alba\User\Contracts;

use Alba\Core\Contracts\ResourceInterface;

interface UserResourceInterface extends ResourceInterface {

	/**
	 * Log the user in.
	 *
	 * @param array $params to overload
	 */
	public function login();

	/**
	 * Log the user out.
	 *
	 */
	public function logout();
}