<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\FanficController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeriesController;
use App\Http\Middleware\ValidateJsonApiDocument;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::withoutMiddleware(ValidateJsonApiDocument::class)
//     ->post('login', LoginController::class)
//     ->name('api.v1.login');

// Rutas con Sanctum
Route::middleware('auth:sanctum')->group(function() {
    //logout
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    //libros
    Route::apiResource('books', BookController::class)
    ->names('api.v1.books')
    ->only('store', 'update', 'destroy');

    //películas
    Route::apiResource('movies', MovieController::class)
    ->names('api.v1.movies')
    ->only('store', 'update', 'destroy');

    //fanfics
    Route::apiResource('fanfics', FanficController::class)
    ->names('api.v1.fanfics')
    ->only('store', 'update', 'destroy');

    //series
    Route::apiResource('series', SeriesController::class)
    ->names('api.v1.series')
    ->only('store', 'update', 'destroy');
});


// Rutas sin Sanctum

//login/register
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

//Rutas Libro
Route::apiResource('books', BookController::class)
->names('api.v1.books')
->only('index', 'show');

//Rutas Película
Route::apiResource('movies', MovieController::class)
->names('api.v1.movies')
->only('index', 'show');

//Rutas Fanfic
Route::apiResource('fanfics', FanficController::class)
->names('api.v1.fanfics')
->only('index', 'show');

//Rutas Series
Route::apiResource('series', SeriesController::class)
->names('api.v1.series')
->only('index', 'show');

// Ruta Bookmark
Route::apiResource('bookmarks', BookmarkController::class)->names('api.v1.bookmarks');
