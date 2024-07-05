<?php

namespace App\Listeners;

use App\Events\ClientTransferred;

class AddClientToQueue extends BasicListener
{

    /**
     * Handle the event.
     */
    public function handle(ClientTransferred $event): void
    {
        $this->queueService->setClientToQueue($event->clientId);
    }
}
