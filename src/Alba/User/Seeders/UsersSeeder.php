<?php namespace Alba\User\Seeders;

/**
 * Seeder for Users module
 *
 * @author daniel <daniel@bexarcreative.com>
 */

class UsersSeeder extends \AlbaCoreSeeder {

    public function run() {

        $this->call('\AlbaPermissionsTableSeeder');
        $this->call('\AlbaRolesTableSeeder');
        $this->call('\AlbaUsersTableSeeder');

    }

}