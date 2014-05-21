<?php namespace Esensi\Core\Seeders;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Seeder as LaravelSeeder;

/**
 * Core Seeder that adds beforeRun and afterRun methods to Laravel's Seeder.
 * Also includes a special saveOrFail() method for showing command line errors.
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class Seeder extends LaravelSeeder {

    /**
     * Run before the database seeds.
     *
     * @return void
     */
    public function beforeRun()
    {
        Model::unguard();
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
        Model::reguard();
    }

    /**
     * Save model or fail by showing errors
     *
     * @param Model $model
     * @param  array $rules Optional rules for save method
     * @return void
     */
    public function saveOrFail(Model $model, array $rules = [])
    {

        if ( is_null($rules) )
        {
            $res = $model->save();
        }
        else
        {
            $res = $model->save($rules);
        }

        if(!$res)
        {
            $class = get_class($model);
            $errors = implode("\n- ", $model->errors()->all());
            $this->command->error("$class could not be seeded:");
            $this->command->line('- '.$errors);
            exit();
        }

    }

}