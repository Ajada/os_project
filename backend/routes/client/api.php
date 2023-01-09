<?php

use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderController::class)
    ->prefix('v1/client')
    ->middleware('set.user_id')
    ->group(function () {
        Route::post('/create-order', 'store');
        Route::get('/get-orders', 'index');
        Route::get('/order/{id}/description', 'show');
        Route::put('/order/{id}/update', 'update');
        Route::delete('/destroy/{id}/order', 'destroy');
    });

Route::controller(ServiceController::class)
    ->prefix('v1/service')
    ->middleware('set.user_id')
    ->group(function () {
        // Route::get();
        Route::post('/register', 'store');
    });