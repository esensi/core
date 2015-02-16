<?php namespace Esensi\Core\Http\Middleware;

use Closure;
use Esensi\Core\Contracts\RateLimiterInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
class RateLimiter implements Middleware, RateLimiterInterface {

    /**
     * The status code to be returned upon rate limiting
     *
     * @var integer
     */
    const RATE_LIMIT_STATUS_CODE = 429;

    /**
     * The wrapped kernel implementation.
     *
     * @var Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The cache manager service.
     *
     * @var Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * The config loader service.
     *
     * @var Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The event dispatcher service.
     *
     * @var Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * The router service.
     *
     * @var Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The translator service.
     *
     * @var Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * The namespace that should be used by the rate limiter.
     *
     * @var string
     */
    protected $namespace = 'esensi/core';

    /**
     * The tag used by the rate limiter.
     *
     * @var string
     */
    protected $tag;

    /**
     * The counter used by the rate limiter.
     *
     * @var integer
     */
    protected $counter = 0;

    /**
     * The timeout state of the client.
     *
     * @var boolean
     */
    protected $inTimeout = false;

    /**
     * Create a new RateLimiter instance.
     *
     * @param  Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->cache      = $app['cache'];
        $this->config     = $app['config'];
        $this->events     = $app['events'];
        $this->router     = $app['router'];
        $this->translator = $app['translator'];
    }

    /**
     * Handle the given request and get the response.
     *
     * @param  Illuminate\Http\Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // @todo limit() should be refactored so as to not
        // rely on Route::current(). This will help get around a
        // limitation with middleware. Right now this handler is
        // not great since it still processes the request even
        // though it responds with a 429. This wastes server
        // resources and could be the vector of a DoS attack.

        // Process the next request first because otherwise
        // the dispatcher for routing won't have completed
        // and therefore the current route will not be available
        // later when we actually limit the request.
        $response = $next($request);

        // Initialize the rate limiting on this client request
        $request = $this->limit($request);

        // Show an error as rate limits are exceeded
        if( $this->isEnabled() && $this->isLimitExceeded() )
        {
            $response = $this->render($request);
        }

        // Add the rate limit headers to the response
        return $this->addHeaders($response);
    }

    /**
     * Check if rate limiting is enabled.
     *
     * @return true
     */
    public function isEnabled()
    {
        return $this->config->get($this->namespace . '::core.rates.enabled');
    }

    /**
     * Check if client is in timeout.
     *
     * @return  boolean
     */
    public function isInTimeout()
    {
        return $this->inTimeout;
    }

    /**
     * Check if rate limit is exceeded.
     *
     * @return  boolean
     */
    public function isLimitExceeded()
    {
        return $this->isInTimeout() || $this->getCounter() >= $this->getLimit();
    }

    /**
     * Get the cache tag for rate limiting.
     *
     * @return  string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Get the counter for rate limiting.
     *
     * @return  integer
     */
    public function getCounter()
    {
        return (int) $this->counter;
    }

    /**
     * Get the limit for rate limiting.
     *
     * @return  integer
     */
    public function getLimit()
    {
        return (int) $this->config->get($this->namespace . '::core.rates.limit', 10);
    }

    /**
     * Get the period for rate limiting.
     *
     * @return  integer
     */
    public function getPeriod()
    {
        return (int) $this->config->get($this->namespace . '::core.rates.period', 1);
    }

    /**
     * Get the timeout for rate limiting.
     *
     * @return  integer
     */
    public function getTimeout()
    {
        return (int) $this->config->get($this->namespace . '::core.rates.timeout', 10);
    }

    /**
     * Limit the HTTP request according to the rates.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Illuminate\Http\Request
     */
    public function limit(Request $request)
    {
        // Remember the old cache settings to reset later
        $oldDriver = $this->config->get('cache.driver');
        $oldTable = $this->config->get('cache.table');

        // Setup cache to use cache table
        $driver = $this->config->get($this->namespace . '::core.rates.cache.driver', 'database');
        $table = $this->config->get($this->namespace . '::core.rates.cache.table', 'cache');
        $this->config->set('cache.driver', $driver);
        $this->config->set('cache.table', $table);

        // Rate limit by IP address
        $tag = $this->config->get($this->namespace . '::core.rates.cache.tag', 'xrate:');
        $tag = sprintf($tag . ':%s', $request->getClientIp());

        // Rate limit by route address
        if( $this->config->get($this->namespace . '::core.rates.routes') && $this->router->current() )
        {
            // Add the route name to the rate tag
            $tag = sprintf($tag . ':%s', $this->router->currentRouteName());

            // Add the route parameters to the rate tag
            $parameters = $this->router->current()->parameters();
            if( ! empty($parameters) && $this->config->get($this->namespace . '::core.rates.parameters'))
            {
                $tag = sprintf($tag . ':%s', implode(',', $parameters));
            }
        }

        // Set the tag that is used
        $this->tag = $tag;

        // Prime rate limiter as a tagged cache
        $this->cache->add($tag, 0, $this->getPeriod());

        // Increment rate limiter count for current request
        $this->counter = (int) $this->cache->get($tag);

        // Increment counter
        if($this->getCounter() < $this->getLimit())
        {
            $this->counter++;
            $this->cache->put($tag, $this->getCounter(), $this->getPeriod());
        }

        // Put request in timeout if not already in timeout
        elseif( ! $this->cache->has($tag.':timeout') )
        {
            // Add timeout
            $this->cache->add($tag.':timeout', true, $this->getTimeout());

            // Fire event listener
            $ip    = $request->getClientIp();
            $route = $this->router->currentRouteName();
            $this->events->fire('esensi.core.rate_exceeded', compact('ip', 'route', 'limit', 'timeout'));
        }

        // Determine if request is in timeout
        $this->inTimeout = $this->cache->has($tag.':timeout');

        // Reset cache settings
        $this->config->set('cache.driver', $oldDriver);
        $this->config->set('cache.table', $oldTable);

        return $request;
    }

    /**
     * Render the error response when a rate limit is exceeded.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function render(Request $request)
    {
        // Get rate exceeded message
        $message = $this->translator->get($this->namespace . '::core.messages.rate_limit_exceeded');
        $error = $this->translator->get($this->namespace . '::core.errors.rate_limit_exceeded', ['timeout' => $this->getTimeout()]);
        $code = self::RATE_LIMIT_STATUS_CODE;
        $data = compact('message', 'error', 'code');

        // Provide a JSON response to API controllers
        if( $request->ajax() || $request->wantsJson() )
        {
            $content = json_encode($data);
            $contentType = 'application/json';
        }

        // Provide an HTML response to UI controllers
        else
        {
            $view = $this->config->get($this->namespace . '::core.views.public.' . $code, 'errors.' . $code);
            $content = view($view, $data);
            $contentType = 'text/html';
        }

        // Make a new response
        return response($content, $code)
            ->header('Content-Type', $contentType);
    }

    /**
     * Add rate limit headers to the response.
     *
     * @param  Symfony\Component\HttpFoundation\Response $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addHeaders(Response $response)
    {
        // Set X-RateLimit headers
        $response->headers->set('X-Ratelimit-Limit', $this->getLimit(), false);
        $response->headers->set('X-Ratelimit-Remaining', $this->getLimit() - $this->getCounter(), false);

        // Enable X-RateLimit-Tag header in debug mode
        if($this->config->get('app.debug') == true)
        {
            $response->headers->set('X-Ratelimit-Tag', $this->getTag(), false);
        }

        return $response;
    }

}
