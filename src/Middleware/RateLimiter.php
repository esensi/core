<?php namespace Esensi\Core\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Rate limiter middleware to ban a user for
 * too many requests within a period of time.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see http://fideloper.com/laravel-http-middleware
 */
class RateLimiter implements HttpKernelInterface {

    /**
     * The status code to be returned upon rate limiting
     *
     * @var integer
     */
    const RATE_LIMIT_STATUS_CODE = 429;

    /**
     * The wrapped kernel implementation.
     *
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected $app;

    /**
     * Create a new RateLimiter instance.
     *
     * @param  \Symfony\Component\HttpKernel\HttpKernelInterface  $app
     * @return void
     */
    public function __construct(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the given request and get the response.
     *
     * @implements HttpKernelInterface::handle
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  int   $type
     * @param  bool  $catch
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        // Handle on passed down request
        $response = $this->app->handle($request, $type, $catch);

        // Rate limit requests
        $response = $this->rateLimit($request, $response);

        return $response;
    }

    /**
     * Limit the requests per minute based on IP
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function rateLimit(Request $request, Response $response)
    {
        $namespace = 'esensi/core::';

        // Only rate limit if enabled
        if( ! $this->app['config']->get($namespace.'core.rates.enabled') )
        {
            return $response;
        }

        // Remember the old cache settings to reset later
        $oldDriver = $this->app['config']->get('cache.driver');
        $oldTable = $this->app['config']->get('cache.table');

        // Setup cache to use cache table
        $driver = $this->app['config']->get($namespace . 'core.rates.cache.driver', 'database');
        $table = $this->app['config']->get($namespace . 'core.rates.cache.table', 'cache');
        $this->app['config']->set('cache.driver', $driver);
        $this->app['config']->set('cache.table', $table);

        // Get requests limit from config
        $limit = $this->app['config']->get($namespace . 'core.rates.limit', 10);
        $period = $this->app['config']->get($namespace . 'core.rates.period', 1);

        // Get request timeout from config
        $timeout = $this->app['config']->get($namespace . 'core.rates.cache.timeout', 10);

        // Rate limit by IP address
        $tag = $this->app['config']->get($namespace . 'core.rates.cache.tag', 'xrate:');
        $tag = sprintf($tag . ':%s', $request->getClientIp());

        // Rate limit by route address
        if( $this->app['router']->current() && $this->app['config']->get($namespace . 'core.rates.routes') )
        {
            // Add the route name to the rate tag
            $tag = sprintf($tag . ':%s', $this->app['router']->currentRouteName());

            // Add the route parameters to the rate tag
            $parameters = $this->app['router']->current()->parameters();
            if( ! empty($parameters) && $this->app['config']->get($namespace . 'core.rates.parameters'))
            {
                $tag = sprintf($tag . ':%s', implode(',', $parameters));
            }
        }

        // Prime rate limiter as a tagged cache
        $this->app['cache']->add($tag, 0, $period);

        // Increment rate limiter count for current request
        $counter = (int) $this->app['cache']->get($tag);

        // Increment counter
        if($counter < $limit)
        {
            $counter++;
            $this->app['cache']->put($tag, $counter, $period);
        }

        // Put request in timeout if not already in timeout
        elseif( ! $this->app['cache']->has($tag.':timeout') )
        {
            // Add timeout
            $this->app['cache']->add($tag.':timeout', true, $timeout);

            // Fire event listener
            $ip    = $request->getClientIp();
            $route = $this->app['router']->currentRouteName();
            Event::fire('esensi.core.rate_exceeded', compact('ip', 'route', 'limit', 'timeout'));
        }

        // Determine if request is in timeout
        $inTimeout = $this->app['cache']->has($tag.':timeout');

        // Reset cache settings
        $this->app['config']->set('cache.driver', $oldDriver);
        $this->app['config']->set('cache.table', $oldTable);

        // Check if counter exceeds rate limit
        if( $counter >= $limit || $inTimeout )
        {
            // Get rate exceeded message
            $message = $this->app['translator']->get($namespace . 'core.messages.rate_limit_exceeded');
            $error = $this->app['translator']->get($namespace . 'core.errors.rate_limit_exceeded', ['timeout' => $timeout]);
            $code = self::RATE_LIMIT_STATUS_CODE;
            $data = compact('message', 'error', 'code');

            // Set status code
            $response->setStatusCode($code);

            // Provide a JSON response to API controllers
            if( $request->ajax() || $request->wantsJson() )
            {
                $response->headers->set('Content-Type', 'application/json');
                $response->setContent(json_encode($data));
            }

            // Provide an HTML response to UI controllers
            else
            {
                $template = $this->app['config']->get($namespace . 'core.views.public.whoops', 'whoops');
                $namespace = $this->app['config']->get($namespace.'core.namespace');
                $response->headers->set('Content-Type', 'text/html');
                $response->setContent( $this->app['view']->make($namespace . $template, $data) );
            }
        }

        // Set X-RateLimit headers
        $response->headers->set('X-Ratelimit-Limit', $limit, false);
        $response->headers->set('X-Ratelimit-Remaining', $limit - (int) $counter, false);

        // Enable X-RateLimit-Tag header in debug mode
        if($this->app['config']->get('app.debug') == true)
        {
            $response->headers->set('X-Ratelimit-Tag', $tag, false);
        }

        return $response;
    }
}
