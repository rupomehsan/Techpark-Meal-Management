<?php

use App\Modules\Management\UserManagement\User\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('users')->group(function () {

        Route::get('', [Controller::class, 'index']);
        Route::get('{slug}', [Controller::class, 'show']);
        Route::post('store', [Controller::class, 'store']);
        Route::post('update/{slug}', [Controller::class, 'update']);
        Route::post('soft-delete', [Controller::class, 'softDelete']);
        Route::post('destroy/{slug}', [Controller::class, 'destroy']);
        Route::post('restore', [Controller::class, 'restore']);

        Route::get('payment-history/{id}', [Controller::class, 'paymentHistory']);
        Route::get('meal-history/{id}', [Controller::class, 'mealHistory']);
        Route::get('user-current-month-blance/{month}', [Controller::class, 'userCurrentMonthBalance']);
        Route::get('user-month-blance/{slug}/{month}', [Controller::class, 'userCurrentBalance']);

        Route::get('department-by-batchname/{department}', [Controller::class, 'departmentBYBatch']);

        Route::post('import', [Controller::class, 'import']);
        Route::post('bulk-action', [Controller::class, 'bulkAction']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('user-profile-update', [Controller::class, 'UserProfileUpdate']);
        Route::post('user-change-password', [Controller::class, 'UserChangePassword']);
    });
});
