<?php

namespace Alba\User\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Alba\User\Models\User;
use Alba\User\Models\Name;
use Alba\User\Models\Role;
use Alba\User\Repositories\Contracts\UserRepositoryInterface;
use Alba\Core\Utils\ProcessResponse;


/**
 * Database implementation of UserRepositoryInterface
 * 
 * @author diego <diego@emersonmedia.com>
 */
class DbUserRepository implements UserRepositoryInterface {


    public static $defaultUserRoles = ['user'];


    public function all() {
        //return User::all();
    }


    public function find($id) {
        //return User::find($id);
    }


    public function findByEmail($email) {
        return User::where('email', $email)->first();
    }


    public function findByToken($token) {
        //return User::where('activation_token', $token)->first();

        $user = DB::table('users')
            ->join('token_user', 'users.id', '=', 'token_user.user_id')
            ->join('tokens', 'tokens.id', '=', 'token_user.token_id')
            ->where('tokens.type', '=', 'activation')
            ->where('tokens.token', '=', $token)
            ->select('users.id')
            ->first();

        /*$queries = DB::getQueryLog();        
        Log::info("Query: " . print_r(end($queries), true));
        Log::info("User: " . print_r($user, true));*/

        if ($user) {
            //Log::info("User id: " . $user->id);
            $user = User::find($user->id);
            /*$queries = DB::getQueryLog();        
            Log::info("2nd Query: " . print_r(end($queries), true));
            Log::info("2nd User: " . print_r($user, true));*/
            return $user;
        } else {
            return null;
        }
    }


    public function findByPasswordResetToken($token) {
        return User::where('password_reset_token', $token)->first();
    }


    public function store($data) {

        /*

        //take user info from data and validate
        $user = new User();
        $user->email = $data['email'];
        //users are created deactivated with no password
        $user->active = false;
        $user->blocked = false;
        if (!$user->validate()) {
            Log::info("Errors in user!");
            return new ProcessResponse(false, $user->errors());            
        }

        //take name info from data and validate
        $name = new Name();
        $name->title = $data['title'];
        $name->first_name = $data['first_name'];
        $name->middle_name = $data['middle_name'];
        $name->last_name = $data['last_name'];
        $name->suffix = $data['suffix'];
        //validate first without checking user, so we don't have to save the user first
        if (!$name->validateBasic()) {
            Log::info("Errors in name!");
            return new ProcessResponse(false, $name->errors());            
        }
                
        //get default Roles to attach to a new user
        $roles = Role::whereIn('name', self::$defaultUserRoles)->get();

        //user and name are correct... save them!
        try {
            DB::transaction(function() use ($user, $name, $roles) {
                
                if (!$user->save()) {
                    //Log::info("ERROR Saving user!");
                    return new ProcessResponse(false, $user->errors());            
                }
                
                //attach Roles to user
                foreach ($roles as $role) {
                    $user->attachRole($role);
                }                

                $name->user()->associate($user);
                
                //Log::info("Saving name...");
                if (!$name->save()) {
                    //Log::info("ERROR Saving name!");
                    return new ProcessResponse(false, $name->errors());
                }

            });
        } catch (\Exception $e) {
            //TODO: add control logic here!
            Log::error("Exception: " . $e);
            return new ProcessResponse(false, 'There was an unexpected error trying to save the user. Please contact a system administrator if this error persists.');            
        }


        return new ProcessResponse(true);
        */
    }



    public function update($id, $data) {
/*
        //search user
        $user = $this->find($id);
        if (!$user) {
            return new ProcessResponse(false, "User not found!");
        }

        //take user info from form and validate
        $userChanged = false;
        if (isset($data['email'])) {
            $userChanged = true;
            $user->email = $data['email'];
        }

        if ($userChanged) {
            if (!$user->validateUpdate()) {                
                return new ProcessResponse(false, $user->errors());            
            }
        }

        //take name info from form and validate
        $nameChanged = false;
        $name = $user->name;
        if (isset($data['title'])) {
            $nameChanged = true;
            $name->title = $data['title'];
        }
        if (isset($data['first_name'])) {
            $nameChanged = true;
            $name->first_name = $data['first_name'];    
        }        
        if (isset($data['middle_name'])) {
            $nameChanged = true;
            $name->middle_name = $data['middle_name'];    
        }        
        if (isset($data['last_name'])) {
            $nameChanged = true;
            $name->last_name = $data['last_name'];    
        }        
        if (isset($data['suffix'])) {
            $nameChanged = true;
            $name->suffix = $data['suffix'];    
        }
        
        if ($nameChanged) {
            if (!$name->validate()) {
                return new ProcessResponse(false, $name->errors());            
            }
        }
        
        //user and name are correct... save them!
        try {
            DB::transaction(function() use ($user, $name, $userChanged, $nameChanged) {
                
                if ($userChanged) {
                    if (!$user->saveUpdate()) {                    
                        return new ProcessResponse(false, $user->errors());            
                    }
                }
                if ($nameChanged) {
                    if (!$name->save()) {                 
                        return new ProcessResponse(false, $name->errors());
                    }
                }

            });
        } catch (\Exception $e) {
            //TODO: add control logic here!
            Log::error("Exception: " . $e);
            return new ProcessResponse(false, 'There was an unexpected error trying to update the user. Please contact a system administrator if this error persists.');            
        }        

        return new ProcessResponse(true);
*/
    }


}
