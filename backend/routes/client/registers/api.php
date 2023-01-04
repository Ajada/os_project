<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Register\AutomobilesController;

Route::controller(AutomobilesController::class)
    ->prefix('v1/register')
    ->group(function () {
        Route::post('/automobile', 'store');
    });
