<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (free)
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'auth'
], function ($router){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me'])->middleware('apijwt');
});


/*
|--------------------------------------------------------------------------
| TASK ROUTES (Protected) 
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => 'apijwt',
    'prefix' => 'task'
], function ($router){
    Route::get('/', [TaskController::class, 'get_by_user']);
    Route::post('/', [TaskController::class, 'create']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::patch('/{id}', [TaskController::class, 'change_status']);
});
