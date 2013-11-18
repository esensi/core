<?php

namespace Alba\Core\Utils;


/**
 * Utility class for representing the responses from object methods
 *
 * @author diego <[email]>
 */
class ProcessResponse {

    const SUCCESS = 'success';
    const ERROR = 'error';
    const WARNING = 'warning';
    const INFO = 'info';

    /**
     * Response type. Can be one of the predefined constants.
     * @var string
     */
    private $type;

    /**
     * Message related to the response status. Could be a MessageBag or a string
     * @var mixed
     */
    private $message;

    /**
     * Varibale used to pass arbitraty data back to the receiver of the response
     * @var mixed
     */
    private $holder;


    /**
     * Return true if type is valid
     */
    public static function validateType($type) {
        if (
            ($type != self::INFO) &&
            ($type != self::ERROR) &&
            ($type != self::SUCCESS) &&
            ($type != self::WARNING)
        ) {
            return false;
        }
        return true;
    }



    /**
     * Constructor
     *
     * @param mixed $type Can be boolean (true = success - false = error), or a string.
     * @param mixed $message A string or a MessageBag
     */
    public function __construct($type, $message = null, $holder = null) {

        if (is_bool($type)) {
            $type = ($type ? self::SUCCESS : self::ERROR);
        }

        if (!self::validateType($type)) {
            throw new Exception("Invalid type parameter", 1);
        }

        $this->type = $type;
        $this->message = $message;
        $this->holder = $holder;
    }


    /**
     * Returns the message
     * @return mixed a string or MessageBag
     */
    public function getMessage() {
        return $this->message;
    }


    /**
     * Returns the holder contents
     * @return mixed
     */
    public function getHolder() {
        return $this->holder;
    }



    /**
     * Returns true if the response is ERROR
     * @return boolean True if fails
     */
    public function isError() {
        if ($this->type === self::ERROR) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Returns true if the response is ERROR
     * @return boolean True if fails
     */
    public function isFailure() {
        if ($this->type === self::ERROR) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Returns true if response is NOT error.
     * @return boolean Ture if not error
     */
    public function isAcceptable() {
        if ($this->type !== self::ERROR) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Returns true if the response is SUCCESS
     * @return boolean Tru if passes
     */
    public function isSuccess() {
        if ($this->type === self::SUCCESS) {
            return true;
        } else {
            return false;
        }
    }

}