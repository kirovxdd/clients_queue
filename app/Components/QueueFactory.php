<?php

namespace App\Components;

use App\Service\Queue\AbstractQueueService;
use App\Service\Queue\MysqlQueueService;

class QueueFactory
{
    public const MYSQL_DATABASE_NAME = 'mysql';

    public function getQueueService(string $serviceDatabaseName = self::MYSQL_DATABASE_NAME): AbstractQueueService
    {
        return $this->getAvailableServices()[$serviceDatabaseName];
    }

    /**
     * @return AbstractQueueService[]
     */
    private function getAvailableServices(): array
    {
        return [
            self::MYSQL_DATABASE_NAME => new MysqlQueueService(),
        ];

    }
}
