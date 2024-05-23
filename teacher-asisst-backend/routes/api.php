
<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::middleware('auth:api')->get('/user', [UserController::class, 'getUser']);