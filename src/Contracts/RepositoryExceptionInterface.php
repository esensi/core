<?php

namespace Esensi\Core\Contracts;

use Throwable;

/**
 * Repository Exception Interface
 *
 */
interface RepositoryExceptionInterface
{
    /**
     * Construct the exception
     *
     * @var    mixed  $bag
     * @var    string  $message
     * @var    integer  $code
     * @var    Throwable  $previous
     * @return RepositoryException
     */
    public function __construct($bag, $message = null, $code = 0, Throwable $previous = null);

    /**
     * Get the bag property
     *
     * @return Illuminate\Support\Contracts\MessageProviderInterface
     */
    public function getBag();

    /**
     * Get the errors from bag
     *
     * @return array
     */
    public function getErrors();

}
