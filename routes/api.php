<?php

use App\Http\Controllers\AddBookmarkController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FanficController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeriesController;
use App\Http\Middleware\ValidateJsonApiDocument;
use Illuminate\Support\Facades\Route;


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

//Rutas Collection
Route::apiResource('collections', CollectionController::class)->names('api.v1.collections');

//Ruta Addbookmark
Route::post('collections/{collection}/bookmarks/{bookmark}', [CollectionController::class, 'addBookmark'])->name('api.v1.bookmark_collection');
