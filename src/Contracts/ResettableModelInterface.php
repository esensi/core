<?php

namespace Esensi\Core\Contracts;

/**
 * Resettable Model Interface
 *
 */
interface ResettableModelInterface
{
    /**
     * Reset a model prior to filling it with attributes.
     *
     * @return Esensi\Core\Models\Model
     */
    public function resetAttributes();

}
