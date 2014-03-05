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
		'validate'							=> 'No se encontró ningún usuario con este correo electrónico y esta contraseña.',
		'authenticate'						=> '¡Lo siento! Somos incapaces de hacer login en su porque su cuenta es bloqueado o en espera de activación por correo electrónico.',
		'show'								=> 'El usuario no se pudo encontrar.',
		'show_by_email'						=> 'No se encontró ningún usuario con este correo electrónico.',
		'show_by_activation_token'			=> 'Señal de activación no se ha encontrado o ha expirado ya.',
		'show_by_password_reset_token'		=> 'Contraseña símbolo de reposición no se ha encontrado o que ya ha expirado.',
		'deactivate'						=> 'El usuario no puede ser desactivado.',
		'unblock'							=> 'El usuario no puede ser desbloqueado: el usuario no puede iniciar sesión',
		'block'								=> 'El usuario no puede ser bloqueado: usuario aún puede iniciar sesión',
		'set_password'						=> 'Esta contraseña no se pudo guardar.',
		'password_reset_not_allowed'		=> 'Un restablecimiento de contraseña no está permitido para este usuario.',
		'password_reset'					=> 'La contraseña no puede ser desarmado.',
		'activate'							=> 'El usuario no se pudo activar: el usuario no puede iniciar sesión',
		'activation_not_allowed'			=> 'Una nueva solicitud de activación no está permitido para este usuario.',
		'update'							=> 'El usuario no se ha podido guardar.',
		'store'								=> 'El usuario no pudo ser creada.',
		'restore'							=> 'El usuario no puede ser restaurado.',
		'destroy'							=> 'El usuario no ha podido eliminar.',
		'no_permission'						=> 'El usuario no tiene ":permission" permiso.',
		'no_results'						=> 'No hay resultados del usuario.',
		'no_roles'							=> 'No hay roles asignados a este usuario.',
		'trashing'							=> 'No se puede eliminar su propia cuenta de usuario.',
		'feature_disabled'					=> 'Esta función se ha desactivado.',
		'old_password_mismatch'				=> 'Contraseña actual proporcionada no coincide.',
		'update_password'					=> 'La contraseña no se pudo guardar.',
		'update_email'						=> 'Dirección de correo electrónico no se pudo actualizar.',
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
		'destroy'							=> 'El usuario ha sido borrado.',
		'restore'							=> 'El usuario ha sido restaurada.',
		'activate'							=> 'El usuario ha sido activado y ahora podrá entrar',
		'deactivate'						=> 'El usuario ha sido desactivado y no podrá entrar',
		'block'								=> 'El usuario ha sido bloqueado y no podrá entrar',
		'unblock'							=> 'El usuario ha sido desbloqueado y ahora podrá entrar',
		'register'							=> 'El usuario ha sido registrado y ahora necesita para activar su cuenta de correo electrónico.',
		'store'								=> 'El usuario ha sido creado.',
		'update'							=> 'El usuario ha sido guardado.',
		'login'								=> 'El usuario ha sido conectado',
		'logout'							=> 'El usuario ha sido desconectado.',
		'set_password'						=> 'El usuario ha guardado una nueva contraseña.',
		'assign_roles'						=> 'Los roles de usuario se han actualizado.',
		'update_email'						=> 'Dirección de correo electrónico se ha actualizado.',
		'update_password'					=> 'La contraseña ha sido actualizado.',
		'reset_password'					=> 'La contraseña ha sido restablecido y un e-mail ha sido enviado a BOB con instrucciones sobre cómo restablecer su contraseña. Si el usuario informa que no recibieron el correo electrónico dentro de 10 a 15 minutos, por favor pídale que compruebe su filtro de correo no deseado, o intentar restablecer su contraseña de nuevo.',
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
		'reset_password'					=> 'Restablecer su contraseña!',
		'reset_activation'					=> 'Activa tu cuenta!',
		'new_account'						=> 'Nueva cuenta creada!',
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
		'not_active'						=> 'El usuario no puede iniciar la sesión porque el usuario no está activa.',
		'no_password'						=> 'El usuario no puede iniciar sesión porque el usuario no tiene contraseña.',
		'is_blocked'						=> 'El usuario no puede iniciar sesión porque el usuario está bloqueada.',
		'never_authenticated'				=> 'Nunca se ha Conectado',
		'never_activated'					=> 'Nunca Activado',
		'never_set_password'				=> 'Nunca Restablecer Contraseña',
		'blocked'							=> 'Bloqueado (No se puede acceder)',
		'not_blocked'						=> 'No Bloqueado (puede acceder)',
		'active'							=> 'Activado',
		'not_active'						=> 'Desactivado',
		'no_password'						=> 'No Establecer Contraseña',
		'has_password'						=> 'Establecer Contraseña',
		'can_login'							=> 'Acceda mascotas',
		'assign_roles_later'				=> 'Puede asignar funciones después de que el usuario ha sido creado. Hasta entonces los roles de usuario predeterminados se asignan al nuevo usuario.',
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