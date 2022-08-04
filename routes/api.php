<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function() {
    Route::match(array('GET','POST'),'/user-destroy', [AuthController::class, 'destroy']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/task-list', [TaskController::class, 'index']);
    Route::get('/task-detail/{id}', [TaskController::class, 'show']);
    Route::post('/task-create', [TaskController::class, 'create']);
    Route::put('/task-edit/{id}', [TaskController::class, 'edit']);
    Route::put('/task-update/{id}', [TaskController::class, 'update']);
    Route::delete('/task-delete/{id}', [TaskController::class, 'destroy']);
});
