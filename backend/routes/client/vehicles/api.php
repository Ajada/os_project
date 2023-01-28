<?php

use App\Http\Controllers\Client\VehiclesController;
use Illuminate\Support\Facades\Route;

Route::controller(VehiclesController::class)
    ->prefix('v1/vehicles')
    ->group(function () {
        Route::get('/collection', 'index');
        Route::post('/register', 'store');
        Route::get('/{param}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
