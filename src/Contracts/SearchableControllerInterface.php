<?php

namespace Esensi\Core\Contracts;

/**
 * Searchable controller interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
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
