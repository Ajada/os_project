<?php

use App\Http\Controllers\Client\OrderController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderController::class)
    ->prefix('v1/client/')
    ->group(function () {
        Route::post('create', 'store');
        Route::post('include/{id}', 'addItemToOrder');
        
        Route::get('collection', 'index');
        Route::get('{id}', 'show');
        
        Route::put('{id}', 'update');
        
        Route::delete('items', 'deleteServiceAndParts');
        Route::delete('{id}', 'destroy');

        Route::get('auth/teste', 'tes');
    });
