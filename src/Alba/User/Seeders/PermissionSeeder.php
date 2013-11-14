<?php namespace Alba\User\Seeders;

use DB;
use Permission;
use Alba\Core\CoreSeeder;

class PermissionSeeder extends CoreSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		parent::beforeRun();

		DB::table('permission_role')->delete();
		DB::table('permissions')->delete();

		$perms = [
				['name' => 'admin', 'display_name' => 'Admin Interface'],
				['name' => 'admin.user', 'display_name' => 'User Admin Interface'],
			];
		foreach($perms as $id => $perm)
		{
			$object = new Permission();
			$object->fill($perm);
			if(!$object->save())
			{	
				$errors = implode("\n- ", $object->errors()->all());
				$this->command->error('Permissions could not be seed:');
				$this->command->line('- '.$errors);
				exit();
			}
		}

		parent::afterRun();
	}

}