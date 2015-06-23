<?php namespace Esensi\Core\Listeners;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

/**
 * Handler for Esensi events.
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class EsensiEventListener {

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
