<?php

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

Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login'])
    ->name('api.auth.login');

Route::post('/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register'])
    ->name('api.auth.register');

Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])
    ->name('api.auth.logout');

Route::get('/payments/{id}', [\App\Http\Controllers\Api\Payment\PaymentController::class, 'show'])
    ->name('api.payments.show');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users', [\App\Http\Controllers\Api\User\UserController::class, 'index'])
        ->name('api.user.index');

    Route::post('/payments', [\App\Http\Controllers\Api\Payment\PaymentController::class, 'store'])
        ->name('api.payments.store');


});
