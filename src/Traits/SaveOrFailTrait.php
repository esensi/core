<?php

namespace Esensi\Core\Traits;

use App\Models\Model;
use Illuminate\Database\Seeder;

/**
 * Trait implementation of SaveOrFailInterface.
 * This trait can be used by seeders and console commands
 * to make saving of self-validating models more verbose.
 */
trait SaveOrFailTrait
{
    /**
     * Save model or fail by showing errors.
     *
     * @param App\Models\Model $model
     * @return void
     */
    public function saveOrFail(Model $model)
    {
        if( ! $model->save() )
        {
            $class = class_basename($model);
            $console = $this instanceof Seeder ? $this->command : $this;

            $errors = implode("\n- ", $model->getErrors()->all());
            $console->error("\n$class could not be seeded:");
            $console->line('- '.$errors);

            $console->comment("\n$class attributes:");
            $console->line($model->toJson(JSON_PRETTY_PRINT));

            exit();
        }
    }
}
