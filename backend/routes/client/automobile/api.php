<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Register\AutomobilesController;

Route::controller(AutomobilesController::class)
    ->prefix('v1/automobiles')
    ->middleware('set.user_id')
    ->group(function () {
        Route::get('/collection', 'index');
        Route::post('/register', 'store');
        Route::get('/{param}/description', 'show');
    });
