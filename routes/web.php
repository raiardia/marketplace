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
});


// Route::get('/admin', [AdminController::class, 'hello']);
// Route::get('/admin', 'admin-dashboard');
// Route::get('/client', 'ClientController@index');
// Route::get('/seller', 'SellerController@index');
