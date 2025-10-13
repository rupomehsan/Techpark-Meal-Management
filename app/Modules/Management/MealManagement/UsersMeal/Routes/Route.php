<?php
use App\Modules\Management\MealManagement\UsersMeal\Controller\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::prefix('user-meals')->group(function () {
        Route::get('user', [Controller::class, 'authGetAll']);
        Route::get('', [Controller::class, 'index']);
        Route::get('{slug}', [Controller::class, 'show']);
        Route::post('store', [Controller::class, 'store']);
        Route::post('userStore', [Controller::class, 'userStore']);
        Route::post('update/{slug}', [Controller::class, 'update']);
        Route::post('update-status', [Controller::class, 'updateStatus']);
        Route::post('soft-delete', [Controller::class, 'softDelete']);
        Route::post('destroy/{slug}', [Controller::class, 'destroy']);
        Route::post('restore', [Controller::class, 'restore']);

        Route::get('role-by-username/{id}', [Controller::class, 'roleByuserName']);
        Route::get('to-day-meal/{date}', [Controller::class, 'toDayMeal']);

        Route::get('user-meal-history/{uid}', [Controller::class, 'userMealHistory']);
        Route::get('users-meal-history', [Controller::class, 'userMealsHistory']);

        Route::get('off-meals/{date}', [Controller::class, 'checkOffMeal']);

        Route::post('import', [Controller::class, 'import']);
        Route::post('bulk-action', [Controller::class, 'bulkAction']);
    });
});
