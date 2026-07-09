<?php

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

Route::get('/index', [AppController::class, 'index'])->name('app.index');
Route::get('/amenities', [AmenityController::class, 'list'])->name('amenities.list');
