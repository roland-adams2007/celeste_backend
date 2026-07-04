<?php

use App\Http\Controllers\Admin\AmenityController;
use Illuminate\Support\Facades\Route;

Route::get('/amenities', [AmenityController::class, 'list'])->name('amenities.list');
