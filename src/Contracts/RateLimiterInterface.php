<?php namespace Esensi\Core\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rate Limiter Interface
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface RateLimiterInterface {

    /**
     * Check if rate limiting is enabled.
     *
     * @return true
     */
    public function isEnabled();

    /**
     * Check if client is in timeout.
     *
     * @return  boolean
     */
    public function isInTimeout();

    /**
     * Check if rate limit is exceeded.
     *
     * @return  boolean
     */
    public function isLimitExceeded();
    /**
     * Get the cache tag for rate limiting.
     *
     * @return  string
     */
    public function getTag();

    /**
     * Get the counter for rate limiting.
     *
     * @return  integer
     */
    public function getCounter();

    /**
     * Get the limit for rate limiting.
     *
     * @return  integer
     */
    public function getLimit();

    /**
     * Get the period for rate limiting.
     *
     * @return  integer
     */
    public function getPeriod();

    /**
     * Get the timeout for rate limiting.
     *
     * @return  integer
     */
    public function getTimeout();

    /**
     * Limit the HTTP request according to the rates.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Illuminate\Http\Request
     */
    public function limit(Request $request);

    /**
     * Render the error response when a rate limit is exceeded.
     *
     * @param  Illuminate\Http\Request $request
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function render(Request $request);

    /**
     * Add rate limit headers to the response.
     *
     * @param  Symfony\Component\HttpFoundation\Response $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addHeaders(Response $response);

}
