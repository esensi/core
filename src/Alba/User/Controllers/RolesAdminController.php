<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;

/**
 * Controller for accessing RolesResource from a backend web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Controllers\RolesController
 */
class RolesAdminController extends \AlbaRolesController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'alba::core.default';

    /**
     * Show confirmation modal
     * 
     * @param integer $id
     * @param string $view
     * @return void
     */
    protected function confirm($id, $view)
    {
        $object = $this->getApi()->show($id);
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

        $this->getApi()->destroy($id);

        return $this->redirect('destroy')
            ->with('message', $this->language('success.destroy'));
    }
    
}