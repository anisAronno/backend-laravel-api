<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PreferenceController;
use App\Http\Controllers\Api\SearchController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::get('/news', [NewsController::class, 'fetchNews']);
Route::get('/user-news', [NewsController::class, 'fetchUserNews'])->middleware('auth:sanctum');

Route::get('/search', [NewsController::class, 'search'])->middleware('auth:sanctum');
Route::post('/search', [SearchController::class, 'store'])->middleware('auth:sanctum');

Route::post('/preference', [PreferenceController::class, 'store'])->middleware('auth:sanctum');
Route::delete('/preference/{preference}', [PreferenceController::class, 'destroy'])->middleware('auth:sanctum');
