<?php

namespace Alba\User\Seeders;

use Alba\Core\Seeders\CoreSeeder;

use Alba\User\Models\Permission;

use Illuminate\Support\Facades\DB;


class PermissionsTableSeeder extends CoreSeeder {

    public function run() {

        $this->beforeRun();

        // Delete existing records from permissions and related pivot tables
        DB::table('permission_role')->delete();
        DB::table('permissions')->delete();

        // Permission name => Display name
        $permissions = [
            'module_users_manage' => 'Manage Users',
            'module_dashboard_view' => 'View Dashboard',
        ];

        // Iterate over permissions saving each to database
        DB::transaction(function () use ($permissions)
        {
            foreach ($permissions as $name => $description)
            {
                $permission = new Permission;
                $permission->name = $name;
                $permission->display_name = $description;
                $this->saveOrFail($permission);
            }
        });

        

        $this->afterRun();

    }

}
