<?php namespace Esensi\Core\Seeders;

use \Magniloquent\Magniloquent\Magniloquent as Model;
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
     * @param  \Magniloquent\Magniloquent\Magniloquent $model
     * @return void
     */
    public function saveOrFail(Model $model)
    {
        $model->save([], true);
        if( ! $model->isSaved() )
        {
            $class = get_class($model);
            $errors = implode("\n- ", $model->errors()->all());
            $this->command->error("$class could not be seeded:");
            $this->command->line('- '.$errors);
            exit();
        }

    }

}
