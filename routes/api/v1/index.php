<?php
use Illuminate\Support\Facades\Route;


/**
 * Auth routes
 */
Route::prefix('auth')->name('auth.')
    ->group(base_path('routes/api/v1/auth.php'));

Route::prefix('admin')->name('admin.')
    ->group(base_path('routes/api/v1/admin.php'));

Route::prefix('customer')->name('customer.')
    ->group(base_path('routes/api/v1/customer.php'));
