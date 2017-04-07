<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Handlers\Events\Component;

use CachetHQ\Cachet\Bus\Events\Component\ComponentWasUpdatedEvent;
use GuzzleHttp\Client;

class SendComponentUpdateSlackMessage
{

    /**
     * Handle the event.
     *
     * @param \CachetHQ\Cachet\Bus\Events\Component\ComponentWasUpdatedEvent $event
     *
     * @return void
     */
    public function handle(ComponentWasUpdatedEvent $event)
    {
        $component = $event->component;

        $client = new Client();

        $client->post(env('SLACK_WEBHOOK_URL'), [
            'json' => $this->getPayload($component)
        ]);
    }

    private function getPayload($component) {
        return [
            "attachments" => [
                [
                    "title" => $component->name . " status updated to " . $this->getStatusDescription($component),
                    "title_link" => "https://status.zingle.me",
                    "color" => $this->getMessageColor($component),
                ]
            ]
        ];
    }
    private function getMessageColor($component) {
        switch($component->status) {
            case 1:
                return 'good';
                break;
            case 2:
                return 'warning';
                break;
            default:
                return 'danger';
        }
    }
    private function getStatusDescription($component) {
        switch($component->status) {
            case 0:
                return 'Unknown :confused:';
                break;
            case 1:
                return 'Operational :thumbsup:';
                break;
            case 2:
                return 'Performance Issues :fearful:';
                break;
            case 3:
                return 'Partial Outage :cold_sweat:';
                break;
            case 4:
                return 'Major Outage :thumbsdown:';
                break;
        }
    }
}
