<?php

namespace App\Service;

use App\Models\Client;
use App\Http\Api\Entities\Client as ClientEntity;

class ClientService
{
    public function createClient(array $clientData): int
    {
        $newClient = Client::query()->firstOrCreate($clientData);
        return $newClient->id;
    }

    public function deleteClientById(int $clientId): void
    {
        Client::query()->where(Client::FIELD_ID, $clientId)->delete();
    }

    /**
     * @return ClientEntity[]
     */
    public function getAllClients(): array
    {
        return $this->convertClientModelsToApiObjects(Client::all()->all());
    }

    public function getClientById(int $clientId): ClientEntity
    {
        return $this->convertClientModelToApiObject(Client::query()->where(Client::FIELD_ID, $clientId)->get()->first());
    }

    /**
     * @param Client[] $models
     * @return ClientEntity[]
     */
    private function convertClientModelsToApiObjects(array $models): array
    {
        return array_map(fn (Client $model): ClientEntity => $this->convertClientModelToApiObject($model), $models);
    }

    private function convertClientModelToApiObject(Client $clientModel): ClientEntity
    {
        $clientEntity = new ClientEntity();

        $clientEntity->id      = $clientModel->id;
        $clientEntity->name    = $clientModel->name;
        $clientEntity->surname = $clientModel->surname;

        return $clientEntity;
    }

    public function isClientValid(int $clientId): bool
    {
        $client = Client::query()->find($clientId);
        return isset($client);
    }
}
