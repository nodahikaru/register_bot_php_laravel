<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('verify-email', [RegisterController::class, 'showVerificationForm'])->name('verify.form');
Route::post('verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email');
Route::view('/verification/notice', 'auth.verification_notice')->name('verification.notice');
Route::view('/verification/success', 'auth.verification_success')->name('verification.success');
Route::view('/verification/error', 'auth.verification_error')->name('verification.error');