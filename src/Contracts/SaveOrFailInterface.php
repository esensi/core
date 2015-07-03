<?php

namespace Esensi\Core\Contracts;

use App\Models\Model;

/**
 * Contract for saving a model or showing an error instead.
 */
interface SaveOrFailInterface
{
    /**
     * Save model or fail by showing errors.
     *
     * @param App\Models\Model $model
     * @return void
     */
    public function saveOrFail(Model $model);
}
