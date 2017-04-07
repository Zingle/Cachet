<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Handlers\Events\IncidentUpdate;

use CachetHQ\Cachet\Bus\Events\IncidentUpdate\IncidentUpdateWasReportedEvent;

class SendIncidentUpdateSlackMessage
{

    /**
     * Handle the event.
     *
     * @param \CachetHQ\Cachet\Bus\Events\Incident\IncidentUpdateWasReportedEvent $event
     *
     * @return void
     */
    public function handle(IncidentUpdateWasReportedEvent $event)
    {
        \Log::info('incident was updated');
        \Log::info(json_encode($event->incident));
        \Log::info(json_encode($event));
    }

}
