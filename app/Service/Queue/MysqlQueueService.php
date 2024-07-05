<?php

namespace App\Service\Queue;

use App\Models\Queue;

class MysqlQueueService extends AbstractQueueService
{
    public function setClientToQueue(int $clientId): void
    {
        Queue::query()->firstOrCreate(
            [Queue::FIELD_CLIENT_ID  => $clientId],
            [Queue::FIELD_CREATED_AT => time()]
        );
    }

    public function processClientQueue(): void
    {
        Queue::query()->orderBy(Queue::FIELD_CREATED_AT)->first()->delete();
    }

    public function deleteClientFromQueueByClientId(int $clientId): void
    {
        Queue::query()->where(Queue::FIELD_CLIENT_ID, $clientId)->delete();
    }

    public function getClientPosition(int $clientId): ?int
    {
        $clientQueue = Queue::query()->where(Queue::FIELD_CLIENT_ID, $clientId)->get()->first();

        return $clientQueue instanceof Queue ? $clientQueue->getClientPosition() : null;
    }

    public function getCurrentClientId(): ?int
    {
        $clientQueue = Queue::query()->orderBy(Queue::FIELD_CREATED_AT)->first();

        return $clientQueue instanceof Queue ? $clientQueue->client_id : null;
    }
}
