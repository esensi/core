<?php namespace Esensi\Core\Http\Middleware;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

/**
 * Logs Esensi Events to Info Log
 *
 * @package Esensi\Esensi
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/Esensi/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class EventLogger {

    /**
     * Handle an incoming request.
     *
     * @todo  move this to Esensi\Core
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Show in info log events fired
        if( Config::get('app.debug') )
        {
            Event::listen('esensi.*', function()
            {
                Log::info( Event::firing() );
            });
        }

        return $next($request);
    }

}
