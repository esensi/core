<?php namespace Esensi\Core\Contracts;

/**
 * Comfirmable controller interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface ConfirmableControllerInterface{

    /**
     * Display a confirmation modal for the specified resource action.
     *
     * @param integer $id
     * @param string $action
     * @return \Illuminate\View\View
     */
    public function confirm($id, $action);

}