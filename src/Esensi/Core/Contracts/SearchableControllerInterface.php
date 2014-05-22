<?php namespace Esensi\Core\Contracts;

/**
 * Searchable controller interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface SearchableControllerInterface{

    /**
     * Display a search modal for the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function search();

}