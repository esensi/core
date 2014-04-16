<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Add remember me token feature to Alba\User model table.
 * This makes it compatible with Laravel 4.1.26
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see http://laravel.com/docs/upgrade#upgrade-4.1.26
 */
class AddRememberTokenToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			// Add table columns
			$table->string('remember_token', 100)->nullable()->after('password');

			// Add table indexes and foreign keys
			$table->index('remember_token');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('remember_token');
		});
	}

}