<?php

namespace Alba\User\Seeders;

use Alba\Core\Seeders\CoreSeeder;

use Carbon\Carbon;

use Alba\User\Models\Name;
use Alba\User\Models\Role;
use Alba\User\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends CoreSeeder {
    

    public function run() {

        $this->beforeRun();

        // Delete existing records from users table
        // @note: user_names table is cascaded in foreign key relationship
        DB::table("users")->delete();

        /*
         * Array of user configurations with associate keys for user (array), name (array), and role (string)
         *
         * @example
         * $userN = ['user' => array, 'name' => array, 'role' => string]
         * $users = [ $user1, $user2, $userN ]
         */
        $users = [
            
            // Admin user
            [
                'user' => [            
                    "email" => "admin@app.dev",
                    "password" => Hash::make("password"), // @todo this should be an Ardent secure field
                    "blocked" => false,
                    "active" => true,
                    'activated_at' => new Carbon(), // @todo this should be a beforeSave hook
                    'password_updated_at' => new Carbon(), // @todo this should be a beforeSave hook
                ],
                'name' => [
                    'title' => 'Mr.',
                    'first_name' => 'Admin',
                    'last_name' => 'User',
                    'suffix' => 'PhD'
                ],
                'role' => 'admin'
            ],

            // Common user
            [
                'user' => [            
                    "email" => "user@app.dev",
                    "password" => Hash::make("password"), // @todo this should be an Ardent secure field
                    "blocked" => false,
                    "active" => true, 
                    'activated_at' => new Carbon(), // @todo this should be a beforeSave hook
                    'password_updated_at' => new Carbon(), // @todo this should be a beforeSave hook
                ],
                'name' => [
                    'title' => 'Mr.',
                    'first_name' => 'Common',
                    'last_name' => 'User',
                    'suffix' => 'MD'
                ],
                'role' => false
            ]
        ];
        
        // Iterate over users saving each to database
        // @note consider user Db::transaction() here to improve efficiency
        DB::transaction(function() use ($users)
        {
            foreach($users as $arr)
            {
                // Save new user
                $user = new User;
                $user->fill($arr['user']);
                $this->saveOrFail($user);

                // Assign role to user
                if($arr['role'])
                {
                    $role = Role::whereName("admin")->first();
                    $user->attachRole($role);
                }

                // Save new name to user
                $name = new Name();
                $name->fill($arr['name']);
                $name->user()->associate($user);
                $this->saveOrFail($name);
            }
        });        

        $this->afterRun();

    }

}
