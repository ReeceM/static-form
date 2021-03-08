<?php

/**
 * Handle Incoming requests to the static forms
 *
 */

use Illuminate\Support\Facades\Route;
use ReeceM\StaticForm\Http\Controllers\Api\ManageStaticTokenController;
use ReeceM\StaticForm\Http\Controllers\HandleStaticFormController;

Route::group([
    'middleware' => config('static-form.middleware.forms'),
], function () {
    Route::post('/{form}', HandleStaticFormController::class)->name('create');
});

Route::prefix('api')
    ->middleware('auth')
    ->middleware(config('static-form.middleware.api'))
    ->group(function () {
        Route::get('token', [ManageStaticTokenController::class, 'index'])->name('token.index');
        Route::post('token', [ManageStaticTokenController::class, 'update'])->name('token.update');
        Route::patch('token', [ManageStaticTokenController::class, 'update'])->name('token.update');
    });
