<?php

class UserResourceTest extends TestCase {

	/**
	 * Default preparation for each test
	 *
	 * @return void
	 */
	public function setUp()
	{
	    parent::setUp();

	    Artisan::call('db:seed', ['--class="RoleSeeder"']);
	}

	/**
	 * Provides user data
	 *
	 * @return array
	 */
	public function provider()
	{
	    return [
	    	[ $this->userData('member') ],
	    	[ $this->userData('staff') ],
	    	[ $this->userData('admin') ]
	    ];
	}

	/**
	 * Provides non-admin user data
	 *
	 * @return array
	 */
	public function nonAdminProvider()
	{
	    return [
	    	[ $this->userData('member') ],
	    	[ $this->userData('staff') ]
	    ];
	}

	/**
	 * Provides user data with a role
	 *
	 * @return array
	 */
	public function userProvider($role)
	{
	    return [
			[ $this->userData($role) ]
	    ];
	}

	/**
	 * Provides user data
	 *
	 * @return array
	 */
	public function userData($role)
	{
	    return [
	    	'first_name' => 'First',
			'last_name' => 'Last',
			'email' => 'user@app.dev',
			'password' => 'password',
			'role' => $role
	    ];
	}

	/**
	 * Role is validated
	 *
	 * @return void
	 */
	public function testCreatingUserRequiresValidRole()
	{
		$this->beRole('admin'); // Requires being admin to get around other logic
		$params = $this->userProvider('member');
		$response = $this->route('POST', 'api.user.store', null, $params);
		$content = $this->assertApiFailure($response, 'user.save.error', true);
	}

	/**
	 * Guest can register as a member
	 *
	 * @dataProvider nonAdminProvider
	 * @return void
	 */
	public function testGuestCanCreateUser($params)
	{
		$response = $this->route('POST', 'api.user.store', null, $params);
		$content = $this->assertApiSuccess($response, 'user.save.success', true);
	}

	/**
	 * Guest can not register as an admin
	 *
	 * @return void
	 */
	public function testGuestCanNotCreateAdmin()
	{
		$params = $this->userProvider('admin');
		$response = $this->route('POST', 'api.user.store', null, $params);
		$content = $this->assertApiFailure($response, 'user.auth.error');
	}

	/**
	 * Authenticated user can not register again
	 *
	 * @dataProvider nonAdminProvider
	 * @return void
	 */
	public function testUserCanNotCreateUser($params)
	{
		$this->beRole($params['role']);
		$response = $this->route('POST', 'api.user.store', null, $params);
		$content = $this->assertApiFailure($response, 'user.auth.error');
	}

	/**
	 * Admin user can create another user
	 *
	 * @dataProvider provider
	 * @return void
	 */
	public function testAdminCanCreateUser($params)
	{
		$this->beRole('admin');
		$response = $this->route('POST', 'api.user.store', null, $params);
		$content = $this->assertApiSuccess($response, 'user.save.success', true);
	}

	/**
	 * Guest can not list users
	 *
	 * @return void
	 */
	public function testGuestCanNotListUsers()
	{
		$response = $this->route('GET', 'api.user.index');
		$content = $this->assertApiFailure($response, 'user.auth.error');
	}

	/**
	 * User can list users
	 *
	 * @dataProvider provider
	 * @return void
	 */
	public function testUserCanListUsers($params)
	{
		$this->beRole($params['role']);
		$response = $this->route('GET', 'api.user.index');
		$content = $this->assertApiCollection($response);
	}

	/**
	 * User can not list trashed users
	 *
	 * @return void
	 */
	public function testUserCanNotListTrashedUsers()
	{
		// Ensure that all of the users are trashed
		User::whereNull('deleted_at')->delete();
		
		// Create a user that's not trashed
		$this->beRole('member');

		$response = $this->route('GET', 'api.user.index');
		$content = $this->assertApiCollection($response);
		
		// Ensure that every user in the collection is not trashed
		foreach($content as $user)
		{
			$this->assertNull($user->deleted_at);
		}
	}

	/**
	 * Admin can list trashed users
	 *
	 * @return void
	 */
	public function testAdminCanListTrashedUsers()
	{
		$this->beRole('admin');

		// Ensure that all of the users are trashed
		User::whereNull('deleted_at')->delete();

		$response = $this->route('GET', 'api.user.index');
		$content = $this->assertApiCollection($response);
		
		// Ensure that every user in the collection is trashed
		foreach($content as $user)
		{
			$this->assertNotNull($user->deleted_at);
		}
	}

	/**
	 * Guest can not see one user
	 *
	 * @return void
	 */
	public function testGuestCanNotShowUser()
	{
		$user = User::first();
		$response = $this->route('GET', 'api.user.show', [ $user->id ]);
		$content = $this->assertApiFailure($response, 'user.auth.error');
	}

	/**
	 * User can see one user
	 *
	 * @dataProvider nonAdminProvider
	 * @return void
	 */
	public function testUserCanShowUser($params)
	{
		$user = $this->beRole($params['role']);
		$response = $this->route('GET', 'api.user.show', [ $user->id ]);
		$content = $this->assertApiObject($response);
	}

	/**
	 * User can not see another user
	 *
	 * @dataProvider nonAdminProvider
	 * @return void
	 */
	public function testUserCanNotShowAnotherUser($params)
	{
		$user = User::first();
		$this->beRole($params['role']);
		$response = $this->route('GET', 'api.user.show', [ $user->id ]);
		$content = $this->assertApiFailure($response, 'user.auth.error');
	}

	/**
	 * Admin can see another user
	 *
	 * @return void
	 */
	public function testAdminCanShowAnotherUser()
	{
		$user = User::first();
		$this->beRole('admin');
		$response = $this->route('GET', 'api.user.show', [ $user->id ]);
		$content = $this->assertApiObject($response);
	}

	/**
	 * Missing user returns error
	 *
	 * @dataProvider provider
	 * @return void
	 */
	public function testCanNotShowMissingUser($params)
	{
		$this->beRole($params['role']);
		$response = $this->route('GET', 'api.user.show', [ -999 ]);
		$content = $this->assertApiFailure($response, 'user.show.error');
	}

	/**
	 * User can not show trashed user
	 *
	 * @dataProvider nonAdminProvider
	 * @return void
	 */
	public function testUserCanNotShowTrashedUser($params)
	{
		// Ensure user is trashed
		$user = User::first();
		$user->delete();

		$this->beRole($params['role']);
		$response = $this->route('GET', 'api.user.show', [ $user->id ]);
		$content = $this->assertApiFailure($response, 'user.show.error');
	}

	/**
	 * Admin can show trashed user
	 *
	 * @return void
	 */
	public function testAdminCanShowTrashedUser()
	{
		// Ensure user is trashed
		$user = User::first();
		$user->delete();

		$this->beRole('admin');
		$response = $this->route('GET', 'api.user.show', [ $user->id ]);
		$content = $this->assertApiObject($response);
	}
}