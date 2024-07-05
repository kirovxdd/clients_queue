<?php

namespace App\Listeners;

use App\Events\RequestToDeleteClientFromQueue;

class DeleteClientFromQueue extends BasicListener
{

    /**
     * Handle the event.
     */
    public function handle(RequestToDeleteClientFromQueue $event): void
    {
        $this->queueService->deleteClientFromQueueByClientId($event->clientId);
    }
}
