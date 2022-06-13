<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FuelEntryController;
use App\Http\Controllers\FuellingController;
use App\Http\Controllers\TripTicketController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('fuel-entries', FuelEntryController::class);
    Route::apiResource('drivers', DriverController::class);
    Route::apiResource('cars', CarController::class);
    Route::get('cars-full', [CarController::class, 'indexFull']);
    Route::apiResource('fuellings', FuellingController::class);
    Route::apiResource('trip-tickets', TripTicketController::class);
});
