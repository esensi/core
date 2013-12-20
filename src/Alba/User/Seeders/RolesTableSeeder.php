<?php namespace Alba\User\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Alba\Core\Seeders\Seeder;

/**
 * Seeder for Roles
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class RolesTableSeeder extends Seeder {

    public function run() {

        $this->beforeRun();

        // Delete existing records from roles and related pivot tables
        DB::table("assigned_roles")->delete();
        DB::table("roles")->delete();

        // Get the roles from config
        $roles = Config::get('alba::role.defaults');

        // Holds cache of all permissions
        $all_permissions = [];

        // Iterate over roles saving each to database
        DB::transaction(function() use ($roles, $all_permissions)
        {
            foreach ($roles as $name => $assigned_perms) 
            {
            
                // Save new role
                $role = new \AlbaRole();
                $role->name = $name;
                $this->saveOrFail($role);

                // Only assign permissions if they need to be
                if(empty($assigned_perms))
                {
                    continue;
                }

                // Check if assigned permissions include all via wildcard
                if (in_array('*', $assigned_perms))
                {
                    // Create cache the first time all permissions is needed
                    if(empty($all_permissions))
                    {
                        $all_permissions = \AlbaPermission::all()->lists('id');
                    }

                    // Use cache of all  permissions
                    $permissions = $all_permissions;
                }            
                else
                {
                    // Get permissions by name
                    $permissions = \AlbaPermission::whereIn('name', $assigned_perms)->lists('id');
                }

                // Assign permissions to role
                $role->perms()->sync($permissions);
            }
        });

        $this->afterRun();

    }

}