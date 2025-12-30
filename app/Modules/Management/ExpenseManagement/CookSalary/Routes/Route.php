<?php

use App\Modules\Management\ExpenseManagement\CookSalary\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix( 'v1')->group(function () {
    Route::prefix('cook-sallary')->group(function () {
        Route::get('cook-salary-by-daily-bajar', [Controller::class,'cookSallaryByDailyBajar']);
        Route::get('', [Controller::class, 'index']);
        Route::get('{slug}', [Controller::class, 'show']);
        Route::post('store', [Controller::class, 'store']);
        Route::post('update/{slug}', [Controller::class, 'update']);
        Route::post('update-status', [Controller::class,'updateStatus']);
        Route::post('soft-delete', [Controller::class, 'softDelete']);
        Route::post('restore', [Controller::class, 'restore']);
        Route::post('destroy/{slug}', [Controller::class, 'destroy']);
        // new route for cook sallary history
        Route::get('cook-sallary-history/{month}', [Controller::class,'cookSallaryHistory']);



        Route::post('import', [Controller::class,'import']);
        Route::post('bulk-action', [Controller::class, 'bulkAction']);
    });
});