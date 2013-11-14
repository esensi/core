<?php namespace Alba\Core;

use Eloquent;
use Seeder;

class CoreSeeder extends Seeder {

	/**
	 * Run before the database seeds.
	 *
	 * @return void
	 */
	public function beforeRun()
	{
		Eloquent::unguard();
	}

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call('\DatabaseSeeder');
	}

	/**
	 * Run after the database seeds.
	 *
	 * @return void
	 */
	public function afterRun()
	{
		Eloquent::reguard();
	}

}