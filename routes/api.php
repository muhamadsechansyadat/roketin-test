<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MoviesController;

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

Route::get('/', function () {
    return response()->json([
        'data' => 'roketin test'
    ]);
});

Route::group(['prefix' => 'movies'], function () {
    Route::get('', [MoviesController::class, 'index'])->name('movies-index');
    Route::post('store', [MoviesController::class, 'store'])->name('movies-store');
    Route::patch('update/{id}', [MoviesController::class, 'update'])->name('movies-update');
    Route::get('search', [MoviesController::class, 'search'])->name('movies-search');
});
