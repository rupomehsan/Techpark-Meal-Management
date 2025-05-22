<?php

use App\Modules\Management\DueListManagement\UserDueList\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix( 'v1')->group(function () {
    Route::prefix('duelist')->group(function () {
        Route::get('', [Controller::class, 'index']);
        Route::get('{slug}', [Controller::class, 'show']);
        Route::post('store', [Controller::class, 'store']);
        Route::post('update/{slug}', [Controller::class, 'update']);
        Route::post('update-status', [Controller::class,'updateStatus']);
        Route::post('soft-delete', [Controller::class, 'softDelete']);
        Route::post('restore', [Controller::class, 'restore']);
        Route::post('destroy/{slug}', [Controller::class, 'destroy']);
    });
});