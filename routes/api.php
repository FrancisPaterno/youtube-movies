<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovieVotesController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SharedMovieController;
use App\Http\Controllers\YoutubeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/search', [YoutubeController::class, 'results']);

Route::resource('/movie', SharedMovieController::class);

Route::post('/vote', [MovieVotesController::class, 'vote']);
