<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rotas User
Route::post('/user/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->get('/user', [UserController::class, 'index']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->get('/user/{id}', [UserController::class, 'show']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->post('/user', [UserController::class, 'store']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->put('/user/{id}', [UserController::class, 'update']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->delete('/user/{id}', [UserController::class, 'destroy']);

// Rotas Client
Route::middleware('auth:sanctum', 'ability:admin,manager')->get('/client', [ClientController::class, 'index']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->get('/client/{id}', [ClientController::class, 'show']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->post('/client', [ClientController::class, 'store']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->put('/client/{id}', [ClientController::class, 'update']);
Route::middleware('auth:sanctum', 'ability:admin,manager')->delete('/client/{id}', [ClientController::class, 'destroy']);

// Rotas Product
Route::middleware('auth:sanctum', 'ability:admin,manager,finance')->get('/product', [ProductController::class, 'index']);
Route::middleware('auth:sanctum', 'ability:admin,manager,finance')->get('/product/{id}', [ProductController::class, 'show']);
Route::middleware('auth:sanctum', 'ability:admin,manager,finance')->post('/product', [ProductController::class, 'store']);
Route::middleware('auth:sanctum', 'ability:admin,manager,finance')->put('/product/{id}', [ProductController::class, 'update']);
Route::middleware('auth:sanctum', 'ability:admin,manager,finance')->delete('/product/{id}', [ProductController::class, 'destroy']);
