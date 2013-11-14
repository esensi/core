<?php namespace Alba\User\Seeders;

use DB;
use Permission;
use Role;
use Alba\Core\CoreSeeder;

class RoleSeeder extends CoreSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		parent::beforeRun();

		DB::table('assigned_roles')->delete();
		DB::table('roles')->delete();

		$roles = ['admin', 'staff', 'member'];
		foreach($roles as $id => $name)
		{
			$object = new Role();
			$object->name = $name;
			if(!$object->save())
			{	
				$errors = implode("\n- ", $object->errors()->all());
				$this->command->error('Roles could not be seed:');
				$this->command->line('- '.$errors);
				exit();
			}

			// Add perms
			if($name == 'admin')
			{
				$permission = new Permission;
				$perms = Permission::where('name','LIKE','admin%')->lists('id');
				$object->perms()->sync($perms);
			}
		}

		parent::afterRun();
	}

}