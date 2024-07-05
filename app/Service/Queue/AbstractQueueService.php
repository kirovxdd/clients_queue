<?php

namespace App\Service\Queue;

abstract class AbstractQueueService
{

    abstract public function setClientToQueue(int $clientId): void;

    abstract public function processClientQueue(): void;

    abstract public function deleteClientFromQueueByClientId(int $clientId): void;

    abstract public function getClientPosition(int $clientId): ?int;

    abstract public function getCurrentClientId(): ?int;
}
