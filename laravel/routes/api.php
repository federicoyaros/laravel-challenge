<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GifController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/gifs/{id}', [GifController::class, 'getGifById']);
    Route::get('/gifs', [GifController::class, 'getGifs']);
    Route::post('/gifs', [GifController::class, 'createFavourite']);
});