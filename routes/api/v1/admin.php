<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\LoanController;

//For admin access

Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::group(['prefix' => 'loan'], function () {
        Route::get('', [LoanController::class, 'get']);
        Route::post('/{loanId}/approve', [LoanController::class, 'approve']);
    });
});

