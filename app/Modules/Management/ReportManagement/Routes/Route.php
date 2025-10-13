<?php

use App\Modules\Management\ReportManagement\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('meal-report')->group(function () {

        Route::get('daily-report', [Controller::class, 'daily']);
        Route::get('monthly-report', [Controller::class, 'monthly']);
        Route::get('monthly-details-report/{month}', [Controller::class, 'monthlyDetails']);
        Route::get('monthly-details-invoice/{month}', [Controller::class, 'monthlyDetailsInvoice']);
        Route::get('users-report', [Controller::class, 'usersReport']);
        Route::get('user-report/{id}', [Controller::class, 'userReport']);
        // Route::get('user-report/{id}/{month}', [Controller::class, 'userReport']);

    });

});
