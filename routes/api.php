<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});






Route::get('/tickets/open', [TicketController::class, 'getAllStatusOpen']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::put('/tickets/{id}/status', [TicketController::class, 'updateStatus']);
    Route::put('/tickets/{id}/progress', [TicketController::class, 'updateProgress']);
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);
    Route::get('/tickets/{id}', [TicketController::class, 'getOneTicket']);
    Route::get('/tickets/client/{clientId}', [TicketController::class, 'getClientTickets']);
    Route::get('/tickets/agent/{agentId}', [TicketController::class, 'getAgentTickets']);
    Route::post('/tickets/{ticketId}/assign', [TicketController::class, 'assign']);
});