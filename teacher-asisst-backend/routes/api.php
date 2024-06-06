<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,RoleController,PermissionController,TeacherController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['api']], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/role=/{id}', [UserController::class, 'getUsersWithRoleId']);

    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);


    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::get('/permissions/{id}', [PermissionController::class, 'show']);
    Route::post('/permissions', [PermissionController::class, 'store']);
    Route::put('/permissions/{id}', [PermissionController::class, 'update']);
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);


    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teacher/{id}', [TeacherController::class, 'showTeacher']);
    Route::post('/register-teacher', [TeacherController::class, 'store']);
    Route::put('/update-teacher/{id}', [TeacherController::class, 'updateTeacher']);
    Route::delete('/delete-teacher/{id}', [TeacherController::class, 'deleteTeacher']);


});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/users',  [UserController::class, 'index']);
