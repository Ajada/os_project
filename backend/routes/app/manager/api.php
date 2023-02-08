<?php

use App\Http\Controllers\Manager\SchemasController;
use Illuminate\Support\Facades\Route;

Route::controller(SchemasController::class)
    ->prefix('v1/schemas')
    ->group(function () {
        Route::post('schema', 'createSchema');
        Route::post('host', 'createHost');
        
        Route::delete('schema', 'destroySchema');
        Route::delete('host', 'destroyHost');
    });