<?php

namespace App\Service\Queue;

use App\Http\Api\Entities\QueuePosition;
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
        $queuePosition = Queue::query()->orderBy(Queue::FIELD_CREATED_AT)->first();

        return $queuePosition instanceof Queue ? $queuePosition->client_id : null;
    }

    /**
     * @return QueuePosition[]
     */
    public function getFullQueue(): array
    {
       $fullQueue = Queue::query()->orderBy(Queue::FIELD_ID)->get()->all();

      return $this->convertFullQueueToQueuePositionEntities($fullQueue);
    }

    /**
     * @param Queue[] $fullQueueSortedById
     * @return QueuePosition[]
     */
    private function convertFullQueueToQueuePositionEntities(array $fullQueueSortedById): array
    {
        $positionEntities = [];

        $clientPosition = 1;
        foreach ($fullQueueSortedById as $queuePositionModel) {
            $clientId = $queuePositionModel->client_id;

            $queuePosition = new QueuePosition();
            $queuePosition->clientId       = $clientId;
            $queuePosition->clientPosition = $clientPosition;

            $positionEntities[$clientId] = $queuePosition;

            $clientPosition++;
        }

        return $positionEntities;
    }
}
