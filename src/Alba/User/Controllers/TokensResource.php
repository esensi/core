<?php namespace Alba\User\Controllers;

use Carbon\Carbon;
use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\Config;
use Alba\Core\Controllers\Resource;
use Alba\Core\Exceptions\ResourceException;
use Alba\User\Models\Token;

/**
 * Custom exception handler for TokensResource
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Exceptions\ResourceException
 */
class TokensResourceException extends ResourceException {}

/**
 * Tokens Resource
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Resource
 */
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
    public function __construct(Token $token)
    {        
        $this->model = $token;
    }

    /**
     * Deletes the token by it's token value
     * 
     * @param string $token
     * @param boolean $force delete
     * @return bool
     */
    public function destroyByToken($token, $force = false)
    {
        $query = $this->model->whereToken($token);
        return ($force) ? $query->forceDelete() : $query->delete();
    }

    /**
     * Creates a new activation type token.
     * 
     * @param UserInterface $user
     * @return Token
     */
    public function createNewActivation(UserInterface $user)
    {
        $model = $this->getModel();
        $ttlHours = Config::get('alba::user.tokens.activation.ttl', null);
        return $this->createNew($model::TYPE_ACTIVATION, $user, $ttlHours);
    }

    /**
     * Creates a new password reset type token.
     * 
     * @param UserInterface $user
     * @return Token
     */
    public function createNewPasswordReset(UserInterface $user)
    {
        $model = $this->getModel();
        $ttlHours = Config::get('alba::user.tokens.password_reset.ttl', null);
        return $this->createNew($model::TYPE_PASSWORD_RESET, $user, $ttlHours);
    }

    /**
     * Generate a new token and store it with its type.
     * 
     * @param string $type
     * @param UserInterface $user
     * @return Token
     */
    public function createNew($type, UserInterface $user, $ttlHours = null)
    {
        $ttlHours = is_null($ttlHours) ? Config::get('alba::user.tokens.ttl', 24) : $ttlHours;
        
        // Generate a token like Illuminate\Auth\Reminders\DatabaseReminderRepository
        $email = $user->getAuthIdentifier();
        $value = str_shuffle(sha1($email.$type.spl_object_hash($this).microtime(true)));
        $token = hash_hmac('sha1', $value, Config::get('app.key'));

        $attributes = [
            'type' => $type,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours($ttlHours),
            ];
        return $this->store($attributes);
    }

}