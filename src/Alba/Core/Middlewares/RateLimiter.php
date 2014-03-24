<?php namespace Alba\Core\Middlewares;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Rate limiter middleware to ban a user for
 * too many requests within a period of time.
 *
 * @author daniel <daniel@bexarcreative.com>
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(SymfonyRequest $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rateLimit(SymfonyRequest $request, SymfonyResponse $response)
    {
        // Only rate limit if enabled
        if( !$this->app['config']->get('alba::core.rates.enabled') )
        {
            return $response;
        }

        // Remember the old cache settings to reset later
        $oldDriver = $this->app['config']->get('cache.driver');
        $oldTable = $this->app['config']->get('cache.table');

        // Setup cache to use cache table
        $driver = $this->app['config']->get('alba::core.rates.cache.driver', 'database');
        $table = $this->app['config']->get('alba::core.rates.cache.table', 'cache');
        $this->app['config']->set('cache.driver', $driver);
        $this->app['config']->set('cache.table', $table);

        // Get requests limit from config
        $limit = $this->app['config']->get('alba::core.rates.limit', 10);

        // Rate limit by IP address
        $tag = $this->app['config']->get('alba::core.rates.cache.tag', 'xrate:');
        $tag = sprintf($tag . ':%s', $request->getClientIp());
        
        // Rate limit by route address
        if( $this->app['config']->get('alba::core.rates.routes') )
        {
            $tag = sprintf($tag . ':%s', $this->app['router']->currentRouteName());
        }

        // Get request timeout from config
        $timeout = $this->app['config']->get('alba::core.rates.cache.timeout', 10);

        // Prime rate limiter as a tagged cache
        $this->app['cache']->add($tag, 0, $timeout);

        // Increment rate limiter count for current request
        $counter = (int) $this->app['cache']->get($tag);

        // Increment counter
        if($counter < $limit)
        {
            $counter++;
            $this->app['cache']->put($tag, $counter, $timeout);
        }

        // Reset cache settings
        $this->app['config']->set('cache.driver', $oldDriver);
        $this->app['config']->set('cache.table', $oldTable);
        
        // Check if counter exceeds rate limit
        if( $counter >= $limit )
        {
            // Show rate exceeded message
            $message = $this->app['translator']->get('alba::core.errors.rate_limit_exceeded', ['timeout' => $timeout]);
            $view = $this->app['view']->make('alba::core.whoops')
                ->with('message', $message)
                ->with('code', self::RATE_LIMIT_STATUS_CODE)
                ->with('error', 'Rate Limit Exceeded');
            $response->setContent($view);

            // Set status code
            $response->setStatusCode(self::RATE_LIMIT_STATUS_CODE);
        }

        // Set X-RateLimit headers
        $response->headers->set('X-Ratelimit-Limit', $limit, false);
        $response->headers->set('X-Ratelimit-Remaining', $limit - (int)$counter, false);

        return $response;
    }
}