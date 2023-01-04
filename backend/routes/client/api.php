<?php

use App\Http\Controllers\Client\OrderController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderController::class)
    ->prefix('v1/client')
    ->group(function () {
        Route::post('/create-order', 'store');
        Route::get('/get-orders', 'index');
    });