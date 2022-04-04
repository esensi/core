<?php

namespace Esensi\Core\Contracts;

/**
 * Searchable controller interface
 *
 */
interface SearchableControllerInterface
{
    /**
     * Display a search modal for the specified resource.
     *
     * @return Illuminate\View\View
     */
    public function search();

}
