<?php namespace Esensi\Core\Seeders;

use App\Models\Model;
use Illuminate\Database\Seeder as BaseSeeder;

/**
 * Core Seeder that adds beforeRun and afterRun methods to Laravel's Seeder.
 * Also includes a special saveOrFail() method for showing command line errors.
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @author diego <diego@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class Seeder extends BaseSeeder {

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
     * Save model or fail by showing errors.
     *
     * @param \Esensi\Core\Models\Model $model
     * @return void
     */
    public function saveOrFail(Model $model)
    {
        if( ! $model->save() )
        {
            $class = class_basename($model);

            $errors = implode("\n- ", $model->getErrors()->all());
            $this->command->error("\n$class could not be seeded:");
            $this->command->line('- '.$errors);

            $this->command->comment("\n$class attributes:");
            $this->command->line($model->toJson(JSON_PRETTY_PRINT));

            exit();
        }
    }

}
