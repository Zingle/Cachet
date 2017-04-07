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
        $client = new Client();

        $client->post(env('SLACK_WEBHOOK_URL'), [
            'json' => $this->getPayload($event)
        ]);
    }

    private function getPayload($event) {
        get_incident_status_description();
        return [
            "attachments" => [
                [
                    "title" => 'Incident ' . $event->update->incident_id . " updated by " . $event->user->username,
                    "title_link" => "https://status.zingle.me/incidents/" . $event->update->incident_id,
                    "color" => get_incident_status_color($event->update->status)
                ]
            ],
            "fields" => [
                [
                    "title" => "Incident",
                    "value" => ($event->update->incident->private ? '(Internal)' : '(Public)') . ' ' . $event->update->incident->name
                ],
                [
                    "title" => "Status",
                    "value" => get_incident_status_description($event->update->status),
                    "short" => true
                ],
                [
                    "title" => "Component",
                    "value" => @$event->update->incident->component->name ?? 'None',
                    "short" => true
                ]
            ]
        ];
    }
}
