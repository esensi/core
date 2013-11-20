<?php namespace Alba\User\Controllers;


use Alba\User\Models\Token;

//use Illuminate\Support\Facades\Log;

//@todo: this should now implement the ResourceInterface!!!
class TokensResource {


    /*public function __construct(Token $token) {
        //@todo change this to injection via IoC
        $this->token = $token;
    }*/


    /**
     * Creates a new token for the specified type, and
     * stores it
     * @param string $type Token type
     * @return Token The new generated token
     */
    public function generateToken($type) {

        //@todo: validate and handle errors

        $token = new Token($type);
        $token->save();
        
        return $token;

    }


}