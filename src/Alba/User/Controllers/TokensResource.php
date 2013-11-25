<?php namespace Alba\User\Controllers;


use Alba\Core\Contracts\ResourceInterface;
use Alba\Core\Utils\StringUtils;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Token;

//use Illuminate\Support\Facades\Log;

class TokensResourceException extends ResourceException {}

class TokensResource implements ResourceInterface {


    /**
     * The Token model
     * @var Alba\User\Models\Token
     */
    protected $token;


    public function __construct(Token $token) {        
        $this->token = $token;
    }


    /**
     * @see ResourceInterface::index
     */
    public function index($params = []) 
    {

    }

    /**
     * @see ResourceInterface::store
     */
    public function store($attributes)
    {

        $this->token->fill($attributes);
        if (!$this->token->save($this->token->rulesForStoring)) {            
            throw new TokensResourceException(
                $this->token->errors(),
                "There were some errors trying to save the token."
            );
        }
        return $this->token;

    }

    /**
     * @see ResourceInterface::show
     */
    public function show($id)
    {

    }

    /**
     * @see ResourceInterface::update
     */
    public function update($id, $attributes)
    {

    }

    /**
     * @see ResourceInterface::destroy
     * 
     */
    public function destroy($id, $force)
    {

        $token = Token::find($id);
        if (!$token) {
            return false;
        }

        $token->delete();

        return true;

    }


    /**
     * Searches the token specified, and deletes it
     * 
     * @param string $token The token string to search and delete
     * @param [type] $force [description]
     * @return bool Whether it deleted the token or not
     */
    public function destroyByToken($token, $force = false) {
        $token = $this->token->whereToken($token)->first();
        if (!$token) {
            return false;
        }
        $token->delete();
        return true;
    }


    /**
     * Creates a new token for the specified type, and
     * stores it.
     * 
     * @param string $type Token type
     * @return Token The new generated token
     */
    public function generateToken($type)
    {

        return $this->store(['type' => $type, 'token' => StringUtils::generateGuid(false)]);

    }


}