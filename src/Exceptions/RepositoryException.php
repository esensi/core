<?php namespace Esensi\Core\Exceptions;

use Esensi\Core\Contracts\RepositoryExceptionInterface;
use Exception;
use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Support\MessageBag;

/**
 * Custom exception handler for repositories
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\RepositoryExceptionInterface
 */
class RepositoryException extends Exception implements RepositoryExceptionInterface{

    /**
     * The bag holder
     *
     * @var Illuminate\Contracts\Support\MessageProvider
     */
    protected $bag;

    /**
     * Construct the exception
     *
     * @var mixed $bag
     * @var string $message
     * @var integer $code
     * @var Exception $previous
     * @return RepositoryException
     */
    public function __construct($bag, $message = null, $code = 0, Exception $previous = null)
    {

        // Make sure there's always a message
        if(is_null($message))
        {
            // Message bag is the message
            if(is_string($bag))
            {
                $message = $bag;
            }

            // Message bag contains the message
            elseif(is_array($bag) && isset($bag['message']))
            {
                $message = $bag['message'];
            }

            // Message doesn't exist so just cast the message bag to a string
            else
            {
                $message = (string) $bag;
            }
        }

        // Make sure the message bag is a message bag
        if( ! $bag instanceof MessageProvider )
        {
            if(is_array($bag))
            {
                $bag = new MessageBag(array_except($bag, ['message']));
            }
            else
            {
                $bag = new MessageBag([]);
            }
        }

        // Save the properties
        $this->bag = $bag;
        $this->message = $message;
        $this->code = $code;
        $this->previous = $previous;
    }

    /**
     * Get the bag property
     *
     * @return Illuminate\Contracts\Support\MessageProvider
     */
    public function getBag()
    {
        return $this->bag;
    }

    /**
     * Get the errors from bag
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->getBag()->toArray();
    }

}
