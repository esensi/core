<?php

namespace Esensi\Core\Traits;

/**
 * Trait implementation of searchable controller interface.
 *
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
        return $this->modal('search');
    }

}
