<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SellerController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/example-page', 'example-page');
Route::view('/example-auth', 'example-auth');
Route::view('/another-auth', 'another-auth');

Route::get('/admin/login', function () {
    return view('back.pages.admin.auth.login');
})->name('admin.login');

Route::post('/admin/login_handler', [AdminController::class, 'loginHandler'])->name('login_handler');

Route::get('/admin/home', function () {
    return view('back.pages.admin.home');
})->name('admin.home');

Route::post('/admin/logout', [AdminController::class, 'logoutHandler'])->name('admin.logout_handler');

Route::get('/admin/forgot-password', [AdminController::class, 'forgotPassword'])->name('admin.forgot');

Route::post('/admin/send-password-reset-link', [AdminController::class, 'sendPasswordResetLink'])->name('admins.send_password_reset_link');

Route::get('/admin/password/reset/{token}', [AdminController::class, 'resetPassword'])->name('admin.password-reset');

// Route::get('/admin', [AdminController::class, 'hello']);
// Route::get('/admin', 'admin-dashboard');
// Route::get('/client', 'ClientController@index');
// Route::get('/seller', 'SellerController@index');
