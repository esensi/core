<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Create tokens pivot table for Alba\Token model
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Models\Token
 */
class CreateTokenUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('token_user', function(Blueprint $table)
		{
            // Add table columns
            $table->integer('token_id')->unsigned();
            $table->integer('user_id')->unsigned();

            // Add table indexes and foreign keys
            $table->primary(array('token_id', 'user_id'));
            $table->foreign('token_id')->references('id')->on('tokens')->onDelete('cascade');
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
		Schema::drop('token_user');
	}

}