<?php

use App\Modules\Management\ExpenseManagement\DailyBajar\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('daily-bajars')->group(function () {

        Route::get('', [Controller::class, 'index']);
        
        Route::post('store', [Controller::class, 'store']);
        Route::post('update/{slug}', [Controller::class, 'update']);
        Route::post('update-status', [Controller::class, 'updateStatus']);
        Route::post('soft-delete', [Controller::class, 'softDelete']);
        Route::post('restore', [Controller::class, 'restore']);
        Route::post('destroy/{slug}', [Controller::class, 'destroy']);

        Route::post('import', [Controller::class, 'import']);
        Route::post('bulk-action', [Controller::class, 'bulkAction']);
        // route for date wise data
        Route::get('date-wise-data/{date}', [Controller::class, 'dateWiseData']);

        // route for date wise total expense
        Route::get('expense-date', [Controller::class, 'expenseData']);
        Route::get('{slug}', [Controller::class, 'show']);
        
    });
});