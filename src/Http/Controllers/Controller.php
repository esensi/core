<?php

namespace Esensi\Core\Http\Controllers;

use Esensi\Core\Http\Apis\Api;
use Esensi\Core\Contracts\ApiAncestryControllerInterface;
use Esensi\Core\Contracts\ExceptionHandlerInterface;
use Esensi\Core\Traits\PublicControllerTrait;

/**
 * Public controller for non-administrative UIs.
 *
 * @see Esensi\Core\Http\Apis\Api
 * @see Esensi\Core\Traits\PublicControllerTrait
 */
abstract class Controller extends Api implements
    ApiAncestryControllerInterface,
    ExceptionHandlerInterface
{
    /**
     * Make controller use the public traits.
     *
     * @see Esensi\Core\Traits\PublicControllerTrait
     */
    use PublicControllerTrait;

    /**
     * The layout that should be used for responses.
     *
     * @var string
     */
    protected $layout = 'esensi/core::core.public.default';

}
