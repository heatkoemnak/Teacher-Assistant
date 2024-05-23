<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);


Route::get('/profiles', [ProfileController::class, 'index']);
Route::get('/profiles/{id}', [ProfileController::class, 'show']);
Route::post('/profiles', [UserController::class, 'store']);
Route::put('/profiles/{id}', [UserController::class, 'update']);
Route::delete('/profiles/{id}', [UserController::class, 'destroy']);

Route::get('/', function () {
    return view('welcome');
});
