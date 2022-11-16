<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Equipment\EquipmentController;
use App\Http\Controllers\Test\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth/token', [\App\Http\Controllers\Auth\AuthController::class, 'token']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function($routes) {
    Route::apiResource('dashboard', DashboardController::class);
    Route::apiResource('equipment', EquipmentController::class);
    Route::apiResource('test', TestController::class);
});
