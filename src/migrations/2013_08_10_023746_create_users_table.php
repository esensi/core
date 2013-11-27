<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Create user table for Alba\User model
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Seeders\UsersTableSeeder
 * @see Alba\User\Models\User
 */
class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
            // Add table columns
            $table->increments('id');
            $table->string('email', 128)->unique();
            $table->string('password', 256)->nullable();
            $table->boolean('active')->default(false);
            $table->boolean('blocked')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('activated_at')->nullable();
			$table->timestamp('password_updated_at')->nullable();
            $table->timestamp('authenticated_at')->nullable();

            // Add table indexes and foreign keys
            $table->index('email');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}