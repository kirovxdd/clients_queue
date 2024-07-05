<?php

namespace App\Http\Controllers;

use App\Components\QueueFactory;
use App\Service\ClientService;
use App\Service\Queue\AbstractQueueService;

abstract class Controller
{
    protected ClientService $clientService;
    protected AbstractQueueService $queueService;

    public function __construct(ClientService $clientService)
    {
        $queueFactory = new QueueFactory();
        $this->clientService = $clientService;
        $this->queueService  = $queueFactory->getQueueService();
    }
}
