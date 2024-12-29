<?php

use App\Http\Controllers\Api\V1\AppointmentApiController;
use App\Http\Controllers\AppointmentController;
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



Route::post('/appointment/create', [AppointmentApiController::class, 'create']);

Route::post('/appointments-table', [AppointmentController::class, 'appointmentsTable']);

Route::post('/appointment/getLastByPhone', [AppointmentApiController::class, 'getLastAppointmentByPhone']);
