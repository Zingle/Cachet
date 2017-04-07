<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Handlers\Events\Incident;

use CachetHQ\Cachet\Bus\Events\Incident\IncidentWasCreatedEvent;
use CachetHQ\Cachet\Bus\Events\Incident\IncidentWasReportedEvent;
use CachetHQ\Cachet\Models\Subscriber;
use GuzzleHttp\Client;
use Illuminate\Contracts\Mail\MailQueue;
use Illuminate\Mail\Message;
use McCool\LaravelAutoPresenter\Facades\AutoPresenter;

class SendIncidentCreatedSlackMessage
{

    /**
     * Handle the event.
     *
     * @param \CachetHQ\Cachet\Bus\Events\Incident\IncidentWasCreatedEvent $event
     *
     * @return void
     */
    public function handle(IncidentWasCreatedEvent $event)
    {
        \Log::info('incident was created');
        \Log::info(json_encode($event->incident));
        \Log::info(json_encode($event));
        $client = new Client();

        $client->post(env('SLACK_WEBHOOK_URL'), [
            'json' => $this->getPayload($event)
        ]);
    }

    private function getPayload($event) {
        return [
            "attachments" => [
                [
                    "title" => ($event->incident->visible ? '[Public]' : '[Internal]') . ' New Incident: ' . $event->incident->name,
                    "title_link" => "https://status.zingle.me/incidents/" . $event->incident->id,
                    "text" => $event->incident->message,
                    "color" => get_incident_status_color($event->incident->status),
                    "fields" => [
                        [
                            "title" => "Status",
                            "value" => get_incident_status_description($event->incident->status),
                            "short" => true
                        ],
                        [
                            "title" => "Component",
                            "value" => @$event->incident->component->name ?? 'None',
                            "short" => true
                        ]
                    ]
                ],
            ],
        ];
    }

}
