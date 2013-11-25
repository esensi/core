<?php namespace Alba\Core\Exceptions;

use \Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;

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
        parent::__construct($message, $code, $previous);
        $this->messageBag = $messageBag;
    }

    /**
     * Returns the messageBag property
     *
     * @return mixed Illuminate\Support\MessageBag
     */
    public function getMessageBag()
    {
        return $this->messageBag;
    }

    /**
     * Handles exceptions with redirect
     *
     * @return Redirect
     */
    public function handleWithRedirect()
    {
        Session::flash('error', true);
        Session::flash('message', $this->getMessage());
        return Redirect::back()->withInput();
    }

    /**
     * Handles exceptions for API output
     *
     * @return array
     */
    public function handleForApi()
    {
        $error = true;
        $message = $this->getMessage();
        $args = compact('error','message');
        return $args;
    }
}