<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Error messages
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the User module.
	|
	*/

	'errors' => [
		'validate'							=> 'User could not be found matching this email address and password.',
		'authenticate'						=> 'User could not be logged in because it either blocked or it is awaiting email activation.',
		'show'								=> 'User could not be found.',
		'show_by_email'						=> 'User could not be found matching this email address.',
		'show_by_activation_token'			=> 'Activation token could not be found or has already expired.',
		'show_by_password_reset_token'		=> 'Password reset token could not be found or has already expired.',
		'deactivate'						=> 'User could not be deactivated.',
		'unblock'							=> 'User could not be unblocked: user cannot log in.',
		'block'								=> 'User could not be blocked: user can still log in.',
		'set_password'						=> 'This password could not be saved.',
		'password_reset_not_allowed'		=> 'A password reset is not allowed for this user.',
		'password_reset'					=> 'The password could not be unset.',
		'activate'							=> 'User could not be activated: user cannot log in.',
		'activation_not_allowed'			=> 'A new activation request is not allowed for this user.',
		'update'							=> 'User could not be saved.',
		'store'								=> 'User could not be created.',
		'restore'							=> 'User could not be restored.',
		'destroy'							=> 'User could not be deleted.',
		'no_permission'						=> 'User does not have ":permission" permission.',
		'no_results'						=> 'There are no user results.',
		'no_roles'							=> 'There are no roles assigned to this user.',
		'trashing'							=> 'You can not delete your own user account.',
		'feature_disabled'					=> 'This feature has been disabled.',
	],

	/*
	|--------------------------------------------------------------------------
	| Success messages
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default success messages used by
	| the User module.
	|
	*/

	'success' => [
		'destroy'							=> 'User has been deleted.',
		'restore'							=> 'User has been restored.',
		'activate'							=> 'User has been activated and will now be able to log in.',
		'deactivate'						=> 'User has been deactivated and will not be able to log in.',
		'block'								=> 'User has been blocked and will not be able to log in.',
		'unblock'							=> 'User has been unblocked and will now be able to log in.',
		'register'							=> 'User has been registered and now needs to activate their account by email.',
		'store'								=> 'User has been created.',
		'update'							=> 'User has been saved.',
		'login'								=> 'User has been logged in.',
		'logout'							=> 'User has been logged out',
		'set_password'						=> 'User has saved a new password.',
		'assign_roles'						=> 'User\'s roles have been updated.',
	],

	/*
	|--------------------------------------------------------------------------
	| Subject lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain subject lines used in emails that
	| the User module sends.
	|
	*/

	'subjects' => [
		'reset_password'					=> 'Reset your password!',
		'reset_activation'					=> 'Activate your account!',
		'new_account'						=> 'New account created!',
	],

	/*
	|--------------------------------------------------------------------------
	| Status message lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain status message lines used by the
	| User module models.
	|
	*/

	'messages' => [
		'not_active'						=> 'User cannot login because the user is not active.',
		'no_password'						=> 'User cannot login because the user has no password.',
		'is_blocked'						=> 'User cannot login because the user is blocked.',
		'never_authenticated'				=> 'Never Logged In',
		'never_activated'					=> 'Never Activated',
		'never_set_password'				=> 'Never Set Password',
		'blocked'							=> 'Blocked (Cannot Login)',
		'not_blocked'						=> 'Not Blocked (Can Login)',
		'active'							=> 'Activated',
		'not_active'						=> 'Deactivated',
		'no_password'						=> 'Password Not Set',
		'has_password'						=> 'Password Set',
		'can_login'							=> 'Login Allowed',
		'assign_roles_later'				=> 'You can assign roles after the user has been created. Until then the default user roles will be assigned to the new user.',
	],

	/*
	|--------------------------------------------------------------------------
	| User name titles and suffixes
	|--------------------------------------------------------------------------
	|
	| The following language lines contain common titles used as prefixes and
	| suffixes for a person's name.
	|
	*/

	'names' => [
		'titles' 							=> ['', 'Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Rev.', 'Hon.'],
		'suffixes'							=> ['', 'Jr.', 'Sr.', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'PhD'],
	],
];