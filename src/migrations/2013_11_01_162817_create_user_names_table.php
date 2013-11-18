<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserNamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_names', function(Blueprint $table) {
			
			// Add table names
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('title', 10)->nullable();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 10)->nullable();
			$table->timestamps();

			// Add table indexes and foreign keys
			$table->index('first_name');
			$table->index('last_name');
			$table->index(['first_name', 'middle_name', 'last_name'], 'name');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_names');
	}

}