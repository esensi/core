<?php

namespace Esensi\Core\Contracts;

/**
 * Resettable Model Interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
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
