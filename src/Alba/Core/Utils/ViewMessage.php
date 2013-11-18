<?php

namespace Alba\Core\Utils;


/**
 * This class allows a controller to pass notices to views, using a predefined and 
 * common interface.
 *
 * @author diego diego@emersonmedia.com
 */
class ViewMessage {
    

    const SUCCESS = "success";
    const INFO = "info";
    const DANGER = "danger";
    const WARNING = "warning";


    /**
     * Type of message. Can be: info, warning, success, error
     * @var string
     */
    private $type;


    /**
     * Message. Cabe a string, a MessageBag, or a ProcessResponse
     * @var mixed
     */
    private $message;


    /**
     * Return true if type is valid
     */
    public static function validateType($type) {
        if (
            ($type != self::SUCCESS) &&
            ($type != self::INFO) &&
            ($type != self::DANGER) &&
            ($type != self::WARNING)
        ) {
            return false;
        }
        return true;
    }


    /**
     * Constructor
     * 
     * @param string $type Type of message. Must be one of the predefined ones
     * @param mixed $message Message, must be string, MessageBag or ProcessResponse
     */
    public function __construct($type, $message) {
        $this->type = $type;
        if (!self::validateType($type)) {
            throw new Exception("Invalid type parameter", 1);            
        }
        $this->message = $message;
    }


    /**
     * Returns the message
     * @return mixed The message
     */
    public function getMessage() {
        return $this->message;
    }


    /**
     * Returns the type of message
     * @return string The type of the message
     */
    public function getType() {
        return $this->type;
    }


    /**
     * Returns a string that represents the class to be used
     * when styling this message
     * @return string
     */
    public function getViewClass() {
        return $this->type;
    }


}