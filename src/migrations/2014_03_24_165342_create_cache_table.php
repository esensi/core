<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Create store table for cache
 *
 * @author daniel <daniel@bexarcreative.com>
 */
class CreateCacheTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cache', function($table)
		{
			// Add table columns
		    $table->string('key')->unique();
		    $table->text('value');
		    $table->integer('expiration');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cache');
	}

}