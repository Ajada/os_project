<?php

use App\Http\Controllers\Manager\SchemasController;
use Illuminate\Support\Facades\Route;

Route::controller(SchemasController::class)
    ->prefix('v1/schemas')
<<<<<<< HEAD
    ->group(function(){
=======
    ->group(function () {
>>>>>>> feature_recreate_methods_to_controllers
        Route::post('create', 'store');
        Route::delete('remove', 'destroy');
    });