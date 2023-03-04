<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;

Route::post('login', [LoginController::class, 'login']);

/**
 * Register Customer and user
 */
Route::group(['prefix' => 'register'], function (){
    Route::post('customer', [RegisterController::class, 'createCustomer']);
    Route::post('admin', [RegisterController::class, 'createAdmin']);
});
