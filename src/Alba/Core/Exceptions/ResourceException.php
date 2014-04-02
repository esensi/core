<?php namespace Alba\Core\Exceptions;

use \Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Contracts\MessageProviderInterface;
use Illuminate\Support\MessageBag;

/**
 * Custom exception handler for Resource controllers
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class ResourceException extends Exception {

    /**
     * The messageBag holder
     *
     * @var Illuminate\Support\Contracts\MessageProviderInterface
     */
    protected $messageBag;

    /**
     * Construct the exception
     *
     * @var mixed $messageBag
     * @var string $message
     * @var integer $code
     * @var Exception $previous
     * @return ResourceException
     */
    public function __construct($messageBag, $message = null, $code = 0, $previous = null)
    {

        // Make sure there's always a message
        if(is_null($message))
        {
            // Message bag is the message
            if(is_string($messageBag))
            {
                $message = $messageBag;
            }

            // Message bag contains the message
            elseif(is_array($messageBag) && isset($messageBag['message']))
            {
                $message = $messageBag['message'];
            }

            // Message doesn't exist so just cast the message bag to a string
            else
            {
                $message = (string) $messageBag;
            }
        }

        // Make sure the message bag is a message bag
        if( !$messageBag instanceof MessageProviderInterface)
        {
            if(is_array($messageBag))
            {
                $messageBag = new MessageBag(array_except($messageBag, ['message']));
            }
            else
            {
                $messageBag = new MessageBag(['error' => false]);
            }
        }

        // save the properties
        $this->messageBag = $messageBag;
        $this->message = $message;
        $this->code = $code;
        $this->previous = $previous;
    }

    /**
     * Get the messageBag property
     *
     * @return Illuminate\Support\Contracts\MessageProviderInterface
     */
    public function getMessageBag()
    {
        return $this->messageBag;
    }

    /**
     * Get the errors from MessageBag
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->getMessageBag()->toArray();
    }

    /**
     * Handles exceptions with redirect
     *
     * @param string $fragment for URL
     * @return Redirect
     */
    public function handleWithRedirect($fragment = null)
    {
        // Get redirect
        $referer = Request::header('referer');
        $redirect = empty($referer) ? Redirect::route('users.signin') : Redirect::back();
        
        // Redirect with fragment
        if($fragment)
        {
            $redirect->setTargetUrl($redirect->getTargetUrl() . '#' . $fragment);
        }

        // Send redirect
        return $redirect->with('message', $this->getMessage())
            ->withErrors($this->getMessageBag())
            ->withInput();
    }

    /**
     * Handles exceptions for API output
     *
     * @return array
     */
    public function handleForApi()
    {
        $errors = $this->getErrors();
        $message = $this->getMessage();
        return compact('errors', 'message');
    }
}