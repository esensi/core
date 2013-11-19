<?php namespace Alba\User\Controllers;

use View;
use Alba\Core\Controllers\CoreController;

class UsersAdminController extends CoreController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'alba::core.default';

	public function login()
	{
		$this->layout->content = View::make('alba::users.login');
	}

}