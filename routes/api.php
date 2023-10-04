<?php

use App\Http\Controllers\SMSController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//public routes
Route::post('send-sms',         [SMSController::class, 'store']);

Route::get('pending-sms/{device_id}',       [SMSController::class, 'pending_sms']);
Route::post('pending-sms/{id}',      [SMSController::class, 'update']);
Route::delete('pending-sms/{id}',      [SMSController::class, 'destroy']);
Route::get('deliver-sms/{device_id}',       [SMSController::class, 'deliver_sms']);
Route::get('error-sms/{device_id}',         [SMSController::class, 'error_sms']);
Route::get('calender-view/{device_id}',         [SMSController::class, 'calender']);
