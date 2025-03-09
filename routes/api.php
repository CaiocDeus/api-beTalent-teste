<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// TODO Verificar rotas permitidas para user
Route::prefix('user')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
});

Route::prefix('transaction')->group(function () {
    Route::post('/', [TransactionController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('ability:admin,manager')->prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::middleware('ability:admin,manager,finance')->prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::middleware('ability:admin')->prefix('gateway')->group(function () {
        Route::get('/', [GatewayController::class, 'index']);
        Route::put('/{id}/change-status', [GatewayController::class, 'changeStatus']);
        Route::put('/{id}/change-priority', [GatewayController::class, 'changePriority']);
    });

    Route::middleware('ability:admin,manager')->prefix('client')->group(function () {
        Route::get('/', [ClientController::class, 'index']);
        Route::get('/{id}', [ClientController::class, 'show']);
        Route::get('/{id}/transactions', [ClientController::class, 'clientWithTransactions']);
        Route::post('/', [ClientController::class, 'store']);
        Route::put('/{id}', [ClientController::class, 'update']);
        Route::delete('/{id}', [ClientController::class, 'destroy']);
    });

    Route::middleware('ability:admin,manager,finance,user')->prefix('transaction')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}/refund', [UserController::class, 'refund']);
    });
});
