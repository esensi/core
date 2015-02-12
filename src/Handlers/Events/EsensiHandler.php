<?php namespace Esensi\Core\Handlers\Events;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

/**
 * Handler for Esensi events.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class EsensiHandler {

    /**
     * Log Esensi events to the info log.
     *
     * @return void
     */
    public function logEvent()
    {
        if( config('app.debug') )
        {
            Log::info( Event::firing() );
        }
    }

}
