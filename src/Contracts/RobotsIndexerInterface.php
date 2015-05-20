<?php namespace Esensi\Core\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Robots Indexer Interface
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface RobotsIndexerInterface {

    /**
     * Render the robots.txt file as an HTTP response.
     *
     * @param  Illuminate\Http\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function render($request);

    /**
     * Add robot headers to the response.
     *
     * @param  Symfony\Component\HttpFoundation\Response $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addHeaders(Response $response);

    /**
     * Check if request is for the robots.txt file.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string  $file name
     * @return boolean
     */
    public function requestIsRobotsFile(Request $request, $file = 'robots.txt');

    /**
     * Check if the environment is disallowed.
     *
     * @return boolean
     */
    public function isDisallowed();

    /**
     * Check if the environment is allowed.
     *
     * @return boolean
     */
    public function isAllowed();

}
