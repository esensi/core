<?php namespace Esensi\Core\Contracts;

/**
 * Comfirmable controller interface
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface ConfirmableControllerInterface{

    /**
     * Display a confirmation modal for the specified resource action.
     *
     * @param string $action
     * @param integer $id (optional)
     * @return Illuminate\View\View
     */
    public function confirm($action, $id = null);

    /**
     * Display a confirmation modal for the specified resource bulk action.
     *
     * @param string $action
     * @param string|array $ids (optional)
     * @return Illuminate\View\View
     */
    public function confirmBulk($action, $ids = null);

}
