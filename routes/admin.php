<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::view('/login', 'back.pages.admin.auth.login')->name('login');

Route::middleware(['auth:admin'])->group(function () {
    Route::view('/home', 'back.pages.admin.home')->name('home');
});