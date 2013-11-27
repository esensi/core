<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Create permission and role tables for Alba\Permission
 * and Alba\Role models
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Seeders\PermissionsTableSeeder
 * @see Alba\User\Seeders\RolesTableSeeder
 * @see Alba\User\Models\Permission
 * @see Alba\User\Models\Role
 */
class EntrustSetupTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the roles table
        Schema::create('roles', function($table)
        {
            // Add table columns
            $table->increments('id');
            $table->string('name', 32)->unique();
            $table->timestamps();

            // Add table indexes and foreign keys
            $table->index('name');
        });

        // Creates the user to roles pivot table
        Schema::create('assigned_roles', function($table)
        {
            // Add table columns
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            
            // Add table indexes and foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');; // assumes a users table
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // Creates the permissions table
        Schema::create('permissions', function($table)
        {
            // Add table columns
            $table->increments('id');
            $table->string('name', 32)->unique();
            $table->string('display_name', 32);
            $table->timestamps();

            // Add table indexes and foreign keys
            $table->index('name');
        });

        // Creates the permission to role pivot table
        Schema::create('permission_role', function($table)
        {
            // Add table columns
            $table->increments('id');
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();
            
            // Add table indexes and foreign keys
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('assigned_roles');
        Schema::drop('roles');
    }

}