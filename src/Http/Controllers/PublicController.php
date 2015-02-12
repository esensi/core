<?php namespace Esensi\Core\Http\Controllers;

use Esensi\Core\Http\Controllers\ApiController as ApiController;
use Esensi\Core\Contracts\ApiAncestryControllerInterface;
use Esensi\Core\Contracts\ExceptionHandlerInterface;
use Esensi\Core\Traits\PublicControllerTrait;

/**
 * Public controller for non-administrative UIs.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Http\Controllers\ApiController
 * @see Esensi\Core\Traits\PublicControllerTrait
 */
abstract class PublicController extends ApiController implements
    ApiAncestryControllerInterface,
    ExceptionHandlerInterface {

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
