<?php

namespace App\Listeners;

use App\Components\QueueFactory;
use App\Service\Queue\AbstractQueueService;


class BasicListener
{
    protected AbstractQueueService $queueService;

    public function __construct()
    {
        $queueFactory = new QueueFactory();
        $this->queueService = $queueFactory->getQueueService();
    }
}
