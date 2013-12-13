<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;
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
     * Display a listing of the trashed resource.
     *
     * @return void
     */
    public function trash()
    {
        Input::merge(['trashed' => 'only']);
        $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param boolean $withTrashed
     * @return void
     */
    public function show($id, $withTrashed = true)
    {
        // Make sure to include trashed
        parent::show($id, $withTrashed);
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
        $object = $this->resources['user']->show($id, true);
        $this->modal('destroy_confirm', ['user' => $object]);
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
     * Show confirmation modal to restore
     * 
     * @param integer $id
     * @return void
     */
    public function restoreConfirm($id)
    {
        $object = $this->resources['user']->show($id, true);
        $this->modal('restore_confirm', ['user' => $object]);
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function restore($id)
    {
        // @todo what about security here?

        $this->apis['user']->restore($id);

        return $this->redirectBack('restore')
            ->with('message', $this->language('success.restore'));
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
        return $this->redirectBack('activate.user', ['id' => $object->id])
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
        
        return $this->redirectBack('deactivate', ['id' => $id])
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

        return $this->redirectBack('block', ['id' => $id])
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
        
        return $this->redirectBack('unblock', ['id' => $id])
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

    /**
     * Update the roles attached to the specified resource in storage.
     *
     * @param int $id of object to update
     * @return Redirect
     */
    public function assignRoles($id)
    {
        // @todo what about security here?

        $object = $this->apis['user']->assignRoles($id);

        return $this->redirectBack('assign_roles', ['id' => $id])
            ->with('message', $this->language('success.assign_roles'));
    }
}