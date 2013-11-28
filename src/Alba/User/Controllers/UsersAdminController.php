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
    
}