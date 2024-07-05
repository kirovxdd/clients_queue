<?php

namespace App\Http\Controllers;

use App\Events\ClientTransferred;
use App\Events\RequestToDeleteClientFromQueue;
use App\Events\RequestToProcessClientQueue;
use App\Http\Requests\DestroyClientRequest;
use App\Http\Requests\GetClientPositionRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AddClientRequest;

class ClientController extends Controller
{
    public function addClient(AddClientRequest $request): JsonResponse
    {
        $clientData = $request->validated();

        if ($this->isNeedToCreateNewClient($clientData)) {
            $clientId = $this->clientService->createClient($clientData);
        } else {
            $clientId = $clientData['id'];
        }

        ClientTransferred::dispatch($clientId);

        return response()->json(status: HTTP_CODE_CREATED);
    }

    public function destroyClient(DestroyClientRequest $request): JsonResponse
    {
        $this->clientService->deleteClientById($request->validated()['id']);

        $this->deleteClientFromQueue($request);

        return response()->json();
    }

    public function deleteClientFromQueue(DestroyClientRequest $request): JsonResponse
    {
        RequestToDeleteClientFromQueue::dispatch($request->validated()['id']);

        return response()->json();
    }

    public function getClientList(): JsonResponse
    {
        return response()->json($this->clientService->getAllClients());
    }

    public function getClientPosition(GetClientPositionRequest $request): JsonResponse
    {
        $clientId = $request->validated()['id'];

        $apiClient = $this->clientService->getClientById($clientId);

        $positionInQueue = $this->queueService->getClientPosition($clientId);
        if ($positionInQueue === null) {
            return response()->json('this client isn`t in the queue');
        }
        $apiClient->positionInQueue = $positionInQueue;

        return response()->json($apiClient);
    }

    public function getCurrentClient(): JsonResponse
    {
        $currentClientId = $this->queueService->getCurrentClientId();

        $apiClient = $this->clientService->getClientById($currentClientId);

        return response()->json($apiClient);
    }

    public function processClientQueue(): JsonResponse
    {
        RequestToProcessClientQueue::dispatch();

        return response()->json();
    }

    private function isNeedToCreateNewClient(array $clientData): bool
    {
        return !isset($clientData['id']);
    }
}
