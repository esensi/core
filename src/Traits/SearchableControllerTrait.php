<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of searchable controller interface.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @author diego <dieog@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\SearchableControllerInterface
 */
trait SearchableControllerTrait {

    /**
     * Display a search modal for the specified resource.
     *
     * @return Illuminate\View\View
     */
    public function search()
    {
        // Render search view
        $this->modal( 'search' );
    }

}
