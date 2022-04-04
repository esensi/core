<?php

namespace Esensi\Core\Contracts;

/**
 * Comfirmable controller interface
 *
 */
interface ConfirmableControllerInterface
{
    /**
     * Display a confirmation modal for the specified resource action.
     *
     * @param  string  $action
     * @param  integer  $id (optional)
     * @return Illuminate\View\View
     */
    public function confirm($action, $id = null);

    /**
     * Display a confirmation modal for the specified resource bulk action.
     *
     * @param  string  $action
     * @param  string|array  $ids (optional)
     * @return Illuminate\View\View
     */
    public function confirmBulk($action, $ids = null);

}
