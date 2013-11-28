<?php namespace Alba\Core\Exceptions;

use \Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
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
     * @var mixed Illuminate\Support\MessageBag
     */
    protected $messageBag;

    /**
     * Constructor for throwing new exception messages 
     *
     * @param mixed $messageBag
     * @param string $message
     * @param long $code
     * @param Exception $previous exception
     * @return void
     */
    public function __construct($messageBag = null, $message = null, $code = 0, Exception $previous = null)
    {
        // Convert a messageBag to a string if missing a message
        if(is_null($message))
        {
            $message = $messageBag;
            if($message instanceof MessageProviderInterface)
            {
                $message = $messageBag->__toString();
            }
        }
        parent::__construct($message, $code, $previous);
        $this->messageBag = $messageBag;
    }

    /**
     * Returns the messageBag property
     *
     * @return mixed Illuminate\Support\Contracts\MessageProviderInterface
     */
    public function getMessageBag()
    {
        return $this->messageBag;
    }

    /**
     * Return an array of errors from the message bag
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->messageBag instanceof MessageProviderInterface ? $this->messageBag->all() : array();
    }

    /**
     * Handles exceptions with redirect
     *
     * There seems to be a bug in Laravel that makes it difficult to use
     * Redirect with flash data which leads to persistent error messages.
     * Capturing the $redirect and then manually calling Session::save()
     * seems to get around this bug.
     *
     * @return Redirect
     */
    public function handleWithRedirect()
    {
        // @todo Why is $this->messageBag a string equal to $message
        // here when in __construct() it's as a MessageBag?
        $redirect = Redirect::back()
            ->with('message', $this->getMessage())
            ->withErrors($this->getMessageBag())
            ->withInput();
        Session::save();
        return $redirect;
    }

    /**
     * Handles exceptions for API output
     *
     * @return array
     */
    public function handleForApi()
    {
        $message = $this->getMessage();
        $errors = $this->getErrors();
        $args = compact('message', 'errors');
        return $args;
    }
}