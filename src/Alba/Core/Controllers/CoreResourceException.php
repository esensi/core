<?php namespace Alba\Core\Controllers;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class CoreResourceException extends Exception {

    /**
     * Handles exceptions with redirect
     *
     * @return Redirect
     */
	function handleWithRedirect()
	{
		Session::flash('error', true);
		Session::flash('message', $this->getMessage());
		$route = Request::server('HTTP_REFERER');
	    return Redirect::to($route)->withInput();
	}

    /**
     * Handles exceptions for API output
     *
     * @return array
     */
	function handleForApi()
	{
		$error = true;
		$message = $this->getMessage();
	    $args = compact('error','message');
	    return $args;
	}
}