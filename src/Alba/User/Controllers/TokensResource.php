<?php namespace Alba\User\Controllers;

use Alba\Core\Controllers\Resource;
use Alba\Core\Utils\StringUtils;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Token;

class TokensResourceException extends ResourceException {}

class TokensResource extends Resource {
    
    /**
     * The exception to be thrown
     * 
     * @var Alba\Core\Exceptions\ResourceException;
     */
    protected $exception = 'TokensResourceException';

    /**
     * Inject dependencies
     **/
    public function __construct(Token $token) {        
        $this->model = $token;
    }

    /**
     * Deletes the token by it's token value
     * 
     * @param string $token
     * @param boolean $force delete
     * @return bool
     */
    public function destroyByToken($token, $force = false) {

        $query = $this->model->whereToken($token);
        return ($force) ? $query->forceDelete() : $query->delete();
    }

    /**
     * Creates a new token for the specified type, and
     * stores it.
     * 
     * @param string $type
     * @return Toke
     */
    public function generateByType($type)
    {
        return $this->store(['type' => $type, 'token' => StringUtils::generateGuid(false)]);
    }

    /**
     * Generates a new activation type token
     * 
     * @return Token
     */
    public function generateActivation()
    {
        $model = $this->getModel();
        return $this->generateByType($model::TYPE_ACTIVATION);
    }

    /**
     * Generates a new password reset type token
     * 
     * @return Token
     */
    public function generatePasswordReset()
    {
        $model = $this->getModel();
        return $this->generateByType($model::TYPE_PASS_RESET);
    }

}