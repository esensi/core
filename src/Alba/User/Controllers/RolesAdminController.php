<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;
use Alba\User\Controllers\RolesController;

/**
 * Controller for accessing RolesResource from a backend web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\RolesController
 */
class RolesAdminController extends RolesController {

    /**
     * Show confirmation modal
     * 
     * @param integer $id
     * @param string $view
     * @return void
     */
    protected function confirm($id, $view)
    {
        $object = $this->resources['role']->show($id);
        $this->modal($view . '_confirm', ['role' => $object]);
    }

    /**
     * Show confirmation modal to destroy
     * 
     * @param integer $id
     * @return void
     */
    public function destroyConfirm($id)
    {
        $this->confirm($id, 'destroy');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function destroy($id)
    {
        // @todo what about security here?

        $this->apis['role']->destroy($id);

        return $this->redirect('destroy')
            ->with('message', $this->language('success.destroy'));
    }
    
}