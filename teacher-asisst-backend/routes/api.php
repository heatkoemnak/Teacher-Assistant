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
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;


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
    Route::post('/classes/create', [ClassController::class, 'store']);
    Route::put('/classes/{id}', [ClassController::class, 'update']);
    Route::delete('/classes/{id}', [ClassController::class, 'destroy']);
    Route::post('classes/{id}/students', [ClassController::class, 'addStudents']);
    Route::get('classes/{teacher}/classes', [ClassController::class, 'showClassWhereTeacherID']);


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


    //admin routes
    Route::get('/admins', [AdminController::class, 'index']);
    Route::get('/admins/{id}', [AdminController::class, 'show']);
    Route::post('/admins/create', [AdminController::class, 'store']);
    Route::put('/admins/{id}/edit', [AdminController::class, 'update']);
    Route::delete('/admins/{id}/delete', [AdminController::class, 'destroy']);

    //login user routes


    Route::post('/login', [AuthController::class, 'login']);


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
    Route::get('/teachers/{id}', [TeacherController::class, 'show']);
    Route::post('/teachers/create', [TeacherController::class, 'store']);
    Route::put('/teachers/{id}/edit', [TeacherController::class, 'update']);
    Route::delete('/teachers/{id}/delete', [TeacherController::class, 'destroy']);
    Route::get('/teachers/{user}/user', [TeacherController::class, 'showTeacherWithUserID']);

    //student routes

    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::post('/students/create', [StudentController::class, 'store']);
    Route::put('/students/{id}/edit', [StudentController::class, 'update']);
    Route::delete('/students/{id}/delete', [StudentController::class, 'destroy']);



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
