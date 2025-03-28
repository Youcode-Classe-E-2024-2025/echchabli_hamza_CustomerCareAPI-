<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ResponseController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});




Route::post('/tickets/open', [TicketController::class, 'getAllTickets']);

Route::get('/tickets/{id}', [TicketController::class, 'getOneTicket']);
Route::middleware('auth:sanctum')->group(function () {
   
    Route::post('/tickets', [TicketController::class, 'store']);
 
    Route::put('/tickets/{id}/status', [TicketController::class, 'updateStatus']);
    Route::put('/tickets/{id}/progress', [TicketController::class, 'updateProgress']);
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);
    
    Route::get('/tickets/client/{clientId}', [TicketController::class, 'getClientTickets']);
    Route::get('/tickets/agent/{agentId}', [TicketController::class, 'getAgentTickets']);
    Route::post('/tickets/{ticketId}/assign', [TicketController::class, 'assign']);
});

Route::get('/tickets', [TicketController::class, 'getAllTickets']);



Route::middleware('auth:sanctum')->group(function () {
Route::post('/responses', [ResponseController::class, 'store']);
Route::get('/responses/ticket/{ticketId}', [ResponseController::class, 'getByTicketId']);


});