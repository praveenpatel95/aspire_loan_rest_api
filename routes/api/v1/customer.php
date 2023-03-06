<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Customer\LoanController;
use App\Http\Controllers\Api\V1\Customer\LoanPaymentController;

Route::middleware(['auth:api'])->group(function () {
    Route::group(['prefix' => 'loan'], function (){
       Route::post('', [LoanController::class, 'create']);
       Route::get('', [LoanController::class, 'get']);

       Route::post('/{loanId}/payment', [LoanPaymentController::class, 'payment']);
    });
});
