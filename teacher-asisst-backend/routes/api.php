<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,RoleController,PermissionController,TeacherController
};
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\Dashboard\AdminController1;
use App\Http\Controllers\Dashboard\UserController1;
use App\Http\Controllers\Dashboard\TeacherController1;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SubjectController;


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

    //user routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/role=/{id}', [UserController::class, 'getUsersWithRoleId']);


    //class routes
    Route::get('/classes', [ClassController::class, 'index']);
    Route::get('/classes/{id}', [ClassController::class, 'show']);
    Route::get('/class/subjects', [ClassController::class, 'classWithSubject']);
    Route::post('/class/attach/subject/{id}', [ClassController::class, 'attachSubject']);


    //subject routes
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/subjects/classes', [SubjectController::class, 'subjectWithClasses']);
    Route::get('/subjects/{id}', [SubjectController::class, 'show']);
    Route::post('/subjects/create', [SubjectController::class, 'store']);
    Route::put('/subjects/{id}/edit', [SubjectController::class, 'update']);
    Route::delete('/subjects/{id}/delete', [SubjectController::class, 'destroy']);
    Route::post('/subject/attach/class/{id}', [SubjectController::class, 'attachClassToSubject']);


    //departments routes

    Route::get('/deps', [DepartmentController::class, 'index']);
    Route::post('/deps/create', [DepartmentController::class, 'store']);
    Route::get('/deps/{id}', [DepartmentController::class, 'show']);
    Route::put('/deps/{id}/edit', [DepartmentController::class, 'update']);
    Route::delete('/deps/{id}/delete', [DepartmentController::class, 'destroy']);

    //register user routes


    Route::post('user-register', [AuthController::class, 'userRegister']);
    Route::post('admin-register', [AuthController::class, 'adminRegister']);
    Route::post('login', [AuthController::class, 'login']);


    //role routes

    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);


    //permission routes

    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::get('/permissions/{id}', [PermissionController::class, 'show']);
    Route::post('/permissions', [PermissionController::class, 'store']);
    Route::put('/permissions/{id}', [PermissionController::class, 'update']);
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);

    //teacher routes


    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teacher/{id}', [TeacherController::class, 'showTeacher']);
    Route::post('/register-teacher', [TeacherController::class, 'store']);
    Route::put('/update-teacher/{id}', [TeacherController::class, 'updateTeacher']);
    Route::delete('/delete-teacher/{id}', [TeacherController::class, 'deleteTeacher']);


});


Route::prefix('auth')->group(function () {



    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });


    Route::middleware(['auth:sanctum', 'role:1'])->group(function () {
        Route::get('admin/dashboard', [AdminController1::class, 'dashboard']);
        // Add more admin routes here
    });

    Route::middleware(['auth:sanctum', 'role:2'])->group(function () {
        Route::get('user/dashboard', [UserController1::class, 'dashboard']);
        // Add more student routes here
    });
    Route::middleware(['auth:sanctum', 'role:3'])->group(function () {
        Route::get('teacher/dashboard', [TeacherController1::class, 'dashboard']);
        // Add more teacher routes here
    });
});
