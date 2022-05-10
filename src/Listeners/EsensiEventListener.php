<?php

namespace Esensi\Core\Listeners;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

/**
 * Handler for Esensi events.
 *
 */
class EsensiEventListener
{
    /**
     * Log Esensi events to the info log.
     *
     * @return void
     */
    public function logEvent()
    {
        if (config('app.debug')) {
            Log::info('');
        }
    }

}
