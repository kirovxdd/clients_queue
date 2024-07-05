<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::post('/client', [ClientController::class, 'addClient']);
Route::delete('/client', [ClientController::class, 'destroyClient']);
Route::get('/clients', [ClientController::class, 'getClientList']);

Route::get('/client/queue', [ClientController::class, 'getClientPosition']);
Route::get('/client/queue/current', [ClientController::class, 'getCurrentClient']);
Route::delete('/client/queue', [ClientController::class, 'deleteClientFromQueue']);
Route::get('/client/queue/process', [ClientController::class, 'processClientQueue']);
