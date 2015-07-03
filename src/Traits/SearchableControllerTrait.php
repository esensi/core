<?php

namespace Esensi\Core\Traits;

/**
 * Trait implementation of searchable controller interface.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @author Diego Caprioli <diego@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\SearchableControllerInterface
 */
trait SearchableControllerTrait
{
    /**
     * Display a search modal for the specified resource.
     *
     * @return Illuminate\View\View
     */
    public function search()
    {
        // Render search view
        return $this->modal( 'search' );
    }

}
