<?php namespace Esensi\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A middleware loosely based on thephpleague/stack-robots to
 * allow or deny access to robots that index the robots.txt
 * file that is traditionally stored in the public path.
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class RobotsIndexer implements Middleware {

    /**
     * Environments that the robots are allowed to index.
     *
     * @var array
     */
    protected $environments = [
        'production',
    ];

    /**
     * Handle the given request and get the response.
     *
     * @param  Illuminate\Http\Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Only allow middleware on disallowed environments
        if( $this->isDisallowed() )
        {
            // Simulate requests for robots.txt and deny
            // robots access to the site.
            if( $this->requestIsRobotsFile($request) )
            {
                return $this->render($request);
            }

            // Add the headers that deny robots access
            $response = $next($request);
            $response = $this->addHeaders($response);
            return $response;
        }

        // Continue processing the request
        return $next($request);
    }

    /**
     * Render the robots.txt file as an HTTP response.
     *
     * @param  Illuminate\Http\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function render($request)
    {
        $response = response("User-Agent: *\nDisallow: /", 200)->headers->set('Content-Type', 'text/plain', true);
        return $response;
    }

    /**
     * Add robot headers to the response.
     *
     * @param  Symfony\Component\HttpFoundation\Response $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function addHeaders(Response $response)
    {
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive', true);
        return $response;
    }

    /**
     * Check if request is for the robots.txt file.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string  $file name
     * @return boolean
     */
    public function requestIsRobotsFile(Request $request, $file = 'robots.txt')
    {
        return $request->is($file);
    }

    /**
     * Check if the environment is disallowed.
     *
     * @return boolean
     */
    public function isDisallowed()
    {
        return ! $this->isAllowed();
    }

    /**
     * Check if the environment is allowed.
     *
     * @return boolean
     */
    public function isAllowed()
    {
        return in_array(App::environment(), $this->environments);
    }

}
