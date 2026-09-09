<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/home', [ApiController::class, 'home']);
Route::get('/saints', [ApiController::class, 'saints']);
Route::get('/vihar', [ApiController::class, 'vihar']);
Route::get('/content', [ApiController::class, 'content']);
Route::get('/content-details/{slug}', [ApiController::class, 'contentDetails']);
Route::get('/events', [ApiController::class, 'events']);
Route::get('/gallery', [ApiController::class, 'gallery']);
Route::get('/videos', [ApiController::class, 'videos']);
Route::get('/panchang', [ApiController::class, 'panchang']);
Route::get('/festivals', [ApiController::class, 'festivals']);
Route::get('/pages', [ApiController::class, 'pages']);
Route::get('/settings', [ApiController::class, 'settings']);
