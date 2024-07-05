<?php

namespace App\Listeners;

use App\Events\RequestToProcessClientQueue;

class ProcessClientQueue  extends BasicListener
{

    /**
     * Handle the event.
     */
    public function handle(RequestToProcessClientQueue $event): void
    {
        $this->queueService->processClientQueue();
    }
}
