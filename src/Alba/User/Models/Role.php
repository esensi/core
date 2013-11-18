<?php

namespace Alba\User\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {

    /**
     * Method added to solve bug:
     *
     * PHP Fatal error:  Class 'Permission' not found in ...../bootstrap/compiled.php
     *
     * 
     * Many-to-Many relations with Permission
     * named perms as permissions is already taken.
     */
    public function perms() {
        // To maintain backwards compatibility we'll catch the exception if the Permission table doesn't exist.
        // TODO remove in a future version
        try {
            return $this->belongsToMany('Alba\User\Models\Permission');
        } catch(Execption $e) {}
    }

}