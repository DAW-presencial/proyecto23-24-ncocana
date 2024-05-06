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

// Rutas sin Sanctum, se aplica auth:sanctum en los controladores

//login/register
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

//Rutas Libro
Route::apiResource('books', BookController::class)->names('api.v1.books');

//Rutas Película
Route::apiResource('movies', MovieController::class)->names('api.v1.movies');

//Rutas Fanfic
Route::apiResource('fanfics', FanficController::class)->names('api.v1.fanfics');

//Rutas Series
Route::apiResource('series', SeriesController::class)->names('api.v1.series');


// Ruta Bookmark
Route::apiResource('bookmarks', BookmarkController::class)->names('api.v1.bookmarks');
