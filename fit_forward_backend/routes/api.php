<?php

use App\Http\Controllers\Api\APIController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [APIController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/plans', [APIController::class, 'plans']);
    Route::post('/workout_complete', [APIController::class, 'workout_complete']);
    Route::get('/user_progress', [APIController::class, 'user_progress']);
});