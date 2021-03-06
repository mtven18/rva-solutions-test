<?php

use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware(['guest'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('register', [UserController::class, 'register']);
        Route::post('login', [UserController::class, 'login']);
    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('info', [UserController::class, 'me']);
        Route::post('logout', [UserController::class, 'logout']);
    });

    Route::apiResource('finance/transfer', TransactionController::class)
        ->only(['index', 'store']);
});
