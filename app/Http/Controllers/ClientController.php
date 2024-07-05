<?php

namespace App\Http\Controllers;

use App\Events\ClientTransferred;
use App\Events\RequestToDeleteClientFromQueue;
use App\Events\RequestToProcessClientQueue;
use App\Http\Api\Entities\Client as ClientEntity;
use App\Http\Api\Entities\QueuePosition;
use App\Http\Requests\DestroyClientRequest;
use App\Http\Requests\GetClientPositionRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AddClientRequest;

class ClientController extends Controller
{
    public function addClient(AddClientRequest $request): JsonResponse
    {
        try {
            $clientData = $request->validated();

            if ($this->isNeedToCreateNewClient($clientData)) {
                $clientId = $this->clientService->createClient($clientData);
            } else {
                $clientId = $clientData['id'];
            }

            ClientTransferred::dispatch($clientId);
        } catch (\Throwable $t) {
            return response()->json(status: HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(status: HTTP_CODE_CREATED);
    }

    public function destroyClient(DestroyClientRequest $request): JsonResponse
    {
        try {
            $this->clientService->deleteClientById($request->validated()['id']);

            $this->deleteClientFromQueue($request);
        } catch (\Throwable $t) {
            return response()->json(status: HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function deleteClientFromQueue(DestroyClientRequest $request): JsonResponse
    {
        RequestToDeleteClientFromQueue::dispatch($request->validated()['id']);

        return response()->json();
    }

    public function getClientList(): JsonResponse
    {
        try {
            $clientEntities = $this->clientService->getAllClients();
            $positionEntities = $this->queueService->getFullQueue();
            $this->matchClientsWithPositions($clientEntities, $positionEntities);
        } catch (\Throwable $t) {
            return response()->json(status: HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return response()->json($clientEntities);
    }

    public function getClientPosition(GetClientPositionRequest $request): JsonResponse
    {
        try {
            $clientId = $request->validated()['id'];

            $apiClient = $this->clientService->getClientById($clientId);

            $positionInQueue = $this->queueService->getClientPosition($clientId);
            if ($positionInQueue === null) {
                return response()->json('this client isn`t in the queue');
            }
            $apiClient->positionInQueue = $positionInQueue;
        }  catch (\Throwable $t) {
            return response()->json(status: HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return response()->json($apiClient);
    }

    public function getCurrentClient(): JsonResponse
    {
        try {
            $currentClientId = $this->queueService->getCurrentClientId();

            $apiClient = $this->clientService->getClientById($currentClientId);
        } catch (\Throwable $t) {
            return response()->json(status: HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return response()->json($apiClient);
    }

    public function processClientQueue(): JsonResponse
    {
        RequestToProcessClientQueue::dispatch();

        return response()->json();
    }

    /**
     * @param ClientEntity[] $clientEntities
     * @param QueuePosition[] $positionEntities
     */
    private function matchClientsWithPositions(array $clientEntities, array $positionEntities): void
    {
        foreach ($clientEntities as $clientEntity) {
            $positionEntity = $positionEntities[$clientEntity->id] ?? null;

            if ($positionEntity === null) {
               continue;
            }

            $clientEntity->positionInQueue = $positionEntity->clientPosition;
        }
    }

    private function isNeedToCreateNewClient(array $clientData): bool
    {
        return !isset($clientData['id']);
    }
}
