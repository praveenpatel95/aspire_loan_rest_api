<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\LoanController;

/**
 * Auth routes
 */
Route::prefix('auth')->name('auth.')
    ->group(base_path('routes/api/v1/auth.php'));

Route::prefix('admin')->name('admin.')
    ->group(base_path('routes/api/v1/admin.php'));

Route::prefix('customer')->name('customer.')
    ->group(base_path('routes/api/v1/customer.php'));

Route::group(['middleware' => ['auth:api'], 'prefix' => 'loan'], function () {
        Route::get('/{loanId}', [LoanController::class, 'getById']);
});
