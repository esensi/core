<?php namespace Alba\Core\Exceptions;


class ResourceException extends \Exception {

    /**
     * The messageBag holder. Can be an instance of Illuminate\Support\MessageBag
     * or a string
     * @var mixed
     */
    protected $messageBag;



    public function __construct($messageBag = null, $message = null)
    {
        parent::__construct($message);
        $this->messageBag = $messageBag;
    }


    /**
     * Returns the messageBag property
     * @return mixed
     */
    public function getMessageBag()
    {
        return $this->messageBag;
    }

}