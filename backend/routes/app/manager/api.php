<?php

use App\Http\Controllers\Manager\SchemasController;
use Illuminate\Support\Facades\Route;

Route::controller(SchemasController::class)
    ->prefix('v1/schemas')
    ->group(function(){
        Route::post('create', 'store');
        Route::delete('remove', 'destroy');
    });