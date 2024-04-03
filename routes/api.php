<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\ValidateJsonApiDocument;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

// Route::withoutMiddleware(ValidateJsonApiDocument::class)
//     ->post('login', LoginController::class)
//     ->name('api.v1.login');



Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');