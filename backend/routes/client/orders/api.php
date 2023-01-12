<?php

use App\Http\Controllers\Client\OrderController;
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

// Route::controller(ServiceController::class)
//     ->prefix('v1/service')
//     ->middleware('set.user_id')
//     ->group(function () {
//         Route::get('/get-collections', 'index');
//         Route::get('/get-item/{id}/collection', 'show');
//         Route::post('/register', 'store');
//         Route::put('/{id}/update', 'update');
//         Route::delete('/destroy/{id}/service', 'destroy');
//     });

// Route::controller(PartsController::class)
//     ->prefix('v1/service-parts/')
//     ->group(function () {
//         Route::get('get-collection', 'index');
//         Route::post('add-parts', 'store');
//         Route::get('get-parts/{id}/service', 'show');
//         Route::put('{id}/update-part', 'update');
//         Route::delete('remove/{id}/part', 'destroy');
//     });