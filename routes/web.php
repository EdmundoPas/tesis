<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');
Route::get('/login', [WebAuthController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [WebAuthController::class, 'store'])->middleware('guest');
Route::post('/logout', [WebAuthController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/panel', [DashboardController::class, 'panel'])->middleware('auth')->name('staff.dashboard');
