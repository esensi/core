<?php namespace Alba\User\Seeders;

use DB;
use Role;
use User;
use Alba\Core\CoreSeeder;

class UserSeeder extends CoreSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		parent::beforeRun();

		DB::table('users')->delete();

		$object = new User();
		$object->fill([
			'first_name'	=> 'Admin',
			'last_name'		=> 'User',
			'email'			=> 'admin@app.dev',
			'password'		=> 'password'
			]);
		if($object->save())
		{
			$role = Role::where('name', 'admin')->first();
			$object->attachRole($role);
		}
		else
		{
			$errors = implode("\n- ", $object->errors()->all());
			$this->command->error('Users could not be seed:');
			$this->command->line('- '.$errors);
			exit();
		}

		parent::afterRun();
	}

}