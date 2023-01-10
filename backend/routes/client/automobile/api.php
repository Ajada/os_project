<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Register\AutomobilesController;

Route::controller(AutomobilesController::class)
    ->prefix('v1/vehicles')
    ->middleware('set.user_id')
    ->group(function () {
        Route::get('/collection', 'index');
        Route::post('/register', 'store');
        Route::get('/{param}/description', 'show');
        Route::put('/{plate}/update', 'update');
        Route::delete('/{id}/destroy-item', 'destroy');
    });
