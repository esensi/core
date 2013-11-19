<?php namespace Alba\User\Controllers;


use Alba\Core\Controllers\CoreResource;
use Alba\User\Models\Token;

use Illuminate\Support\Facades\Log;

class TokensResource extends CoreResource {


    /**
     * Creates a new token for the specified type, and
     * stores it
     * @param string $type Token type
     * @return Token The new generated token
     */
    public function generateToken($type) {

        //@todo: validate and handle errors

        $token = new Token($type);
        
        Log::info('TOKEN: ' . print_r($token, true));

        //@errors being handled as Exception or returning ProcessResponse for returning errors???
        $token->save();
        
        return $token;

    }


}