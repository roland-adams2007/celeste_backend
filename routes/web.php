<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(config('app.redirect_url'));
})->name('frontend');

Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
