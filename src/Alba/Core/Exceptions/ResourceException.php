<?php namespace Alba\Core\Exceptions;

use \Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Contracts\MessageProviderInterface;
//use Illuminate\Support\MessageBag;

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
    public $messageBag;

    /**
     * Get the messageBag property
     *
     * @return mixed Illuminate\Support\Contracts\MessageProviderInterface
     */
    public function getMessageBag()
    {
        return json_decode($this->getMessage());
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
        // here when in __construct() it's as a MessageBag?
        $redirect = Redirect::back()
            ->with('message', end($this->getMessageBag()->message))
            ->withErrors(array_except((array)$this->getMessageBag(), ['message']))
            ->withInput();
        return $redirect;
    }

    /**
     * Handles exceptions for API output
     *
     * @return array
     */
    public function handleForApi()
    {
        return $this->getMessageBag();
    }
}