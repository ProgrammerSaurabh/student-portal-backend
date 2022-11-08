<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

Route::controller(Api\LoginController::class)
    ->group(function () {
        Route::post('login', 'login');

        Route::post('forgot-password', 'forgotPassword');

        Route::post('forgot-password/verify', 'forgotPasswordVerify');
    });

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('logout', [Api\LoginController::class, 'logout']);

        Route::get('profile', [Api\LoginController::class, 'profile']);

        Route::prefix('dashboard')
            ->controller(Api\DashboardController::class)
            ->group(function () {
                Route::get('pie-chart', 'pieChart');

                Route::get('stack-bar', 'stackBar');
            });
    });
