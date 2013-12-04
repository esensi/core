<?php namespace Alba\User\Controllers;

use Alba\User\Controllers\UsersController;

/**
 * Controller for accessing UsersResource from a backend web interface
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\UsersController
 */
class UsersAdminController extends UsersController {

    /**
     * Show confirmation modal
     * 
     * @param integer $id
     * @param string $view
     * @return void
     */
    protected function confirm($id, $view)
    {
        $object = $this->resources['user']->show($id);
        $this->modal($view . '_confirm', ['user' => $object]);
    }

    /**
     * Show confirmation modal to reset activation
     * 
     * @param integer $id
     * @return void
     */
    public function resetActivationConfirm($id)
    {
        $this->confirm($id, 'reset_activation');
    }

    /**
     * Show confirmation modal to reset password
     * 
     * @param integer $id
     * @return void
     */
    public function resetPasswordConfirm($id)
    {
        $this->confirm($id, 'reset_password');
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

        $this->apis['user']->destroy($id);

        return $this->redirect('destroy')
            ->with('message', $this->language('success.destroy'));
    }

    /**
     * Show confirmation modal to activate user
     * 
     * @param integer $id
     * @return void
     */
    public function activateConfirm($id)
    {
        $this->confirm($id, 'activate');
    }

    /**
     * Activate the specified user
     * 
     * @param integer $id
     * @return Redirect
     */
    public function activate($id)
    {
        $object = $this->resources['user']->activate($id);

        // Redirect to user profile
        return $this->redirect('activate.user', ['id' => $object->id])
            ->with('message', $this->language('success.activate'));
    }

    /**
     * Show confirmation modal to deactivate user
     * 
     * @param integer $id
     * @return void
     */
    public function deactivateConfirm($id)
    {
        $this->confirm($id, 'deactivate');
    }

    /**
     * Deactivates the specified user
     * 
     * @param integer $id
     * @return Redirect
     */
    public function deactivate($id)
    {
        // @todo what about security here?

        $object = $this->resources['user']->deactivate($id);
        
        return $this->redirect('deactivate', ['id' => $id])
            ->with('message', $this->language('success.deactivate'));
    }

    /**
     * Show confirmation modal to block user
     * 
     * @param integer $id
     * @return void
     */
    public function blockConfirm($id)
    {
        $this->confirm($id, 'block');
    }

    /**
     * Blocks the specified user
     * 
     * @param integer $id
     * @return Redirect
     */
    public function block($id)
    {
        // @todo what about security here?

        $object = $this->resources['user']->block($id);

        return $this->redirect('block', ['id' => $id])
            ->with('message', $this->language('success.block'));
    }

    /**
     * Unblocks the specified user 
     * 
     * @param integer $id
     * @return Redirect
     */
    public function unblock($id)
    {
        // @todo what about security here?

        $object = $this->resources['user']->unblock($id);
        
        return $this->redirect('unblock', ['id' => $id])
            ->with('message', $this->language('success.unblock'));
    }

    /**
     * Show confirmation modal to unblock user
     * 
     * @param integer $id
     * @return void
     */
    public function unblockConfirm($id)
    {
        $this->confirm($id, 'unblock');
    }
    
}