<?php

namespace Alba\User\Repositories\Contracts;

use Alba\User\Models\User;
use Alba\User\Models\Name;

/**
 * Interface for data access to users
 * 
 * @author  diego <diego@emersonmedia.com>
 */
interface UserRepositoryInterface {


    /**
     * Retrieves all the users
     * 
     * @return Users[]
     */
    public function all();


    /**
     * Returns a user by id
     *
     * @param integer $id Id of user to find
     * @return User
     */
    public function find($id);


    /**
     * Returns a user by email address
     *
     * @param string $email Email address
     * @return User
     */
    public function findByEmail($email);


    /**
     * Returns a user by activation token
     *
     * @param string $token Activation token
     * @return User
     */
    public function findByToken($token);


    /**
     * Returns a user by the password reset token
     * @param string $token Password reset token
     * @return User
     */
    public function findByPasswordResetToken($token);


    /**
     * Saves a new user and it's name to the storage
     *
     * @param array $data Array of user data
     * @return ProcessResponse Object representing the result of this operation
     */
    public function store($data);


    /**
     * Searches for the User indicate by $id and updates all the information as provided
     * in $data array
     * @param  integer $id Id of user to update
     * @param  array $data New user data
     * @return ProcessResponse Object representing the result of this operation
     */
    public function update($id, $data);
    

}