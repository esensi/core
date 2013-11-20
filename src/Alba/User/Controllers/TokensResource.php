<?php namespace Alba\User\Controllers;


use Alba\Core\Contracts\ResourceInterface;
use Alba\Core\Utils\StringUtils;
use Alba\User\Models\Token;

//use Illuminate\Support\Facades\Log;

//@todo: this should now implement the ResourceInterface!!!
class TokensResource implements ResourceInterface {


    /*public function __construct(Token $token) {
        //@todo change this to injection via IoC
        $this->token = $token;
    }*/


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

        $token = new Token();
        $token->fill($attributes);
        if (!$token->save()) {
            //errors
            //throw new TokenStoreException();
            $str = '';
            foreach ($token->errors()->all('- :message') as $message) {
                $str .= $message . "\n";
            }
            throw new \Exception("There were some errors trying to save the token: \n $str");
        }
        return $token;

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

    }


    /**
     * Creates a new token for the specified type, and
     * stores it.
     * @param string $type Token type
     * @return Token The new generated token
     */
    public function generateToken($type)
    {

        return $this->store(['type' => $type, 'token' => StringUtils::generateGuid(false)]);

    }


}