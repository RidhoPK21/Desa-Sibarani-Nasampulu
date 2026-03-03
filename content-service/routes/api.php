<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;

Route::get('/banner', [BannerController::class, 'index']);
Route::post('/banner', [BannerController::class, 'store']);