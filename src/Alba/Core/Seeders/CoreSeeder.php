<?php namespace Alba\Core\Seeders;

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

	/**
     * Save model or fail by showing errors
     *
     * @param Eloquent $model
     * @return void
     */
    public function saveOrFail(Eloquent $model)
    {
	    if(!$model->save())
	    {
	        $class = get_class($model);
	        $errors = implode("\n- ", $model->errors()->all());
	        $this->command->error("$class could not be seeded:");
	        $this->command->line('- '.$errors);
	        exit();
		}
	}
}