<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/language-switch', [LanguageController::class, 'languageSwitch'])->name('language.switch');

require __DIR__ . '/auth.php';

// ROUTES FRONTEND PRUEBAS
Route::get('/prueba', function () {
    return Inertia::render('Prueba');
})->middleware(['auth', 'verified'])->name('prueba');

Route::get('/searchadvanced', function () {
    return Inertia::render('SearchAdvanced');
})->middleware(['auth', 'verified'])->name('searchadvanced');

Route::get('/bookmarkers', function () {
    return Inertia::render('BookMarkers');
})->middleware(['auth', 'verified'])->name('bookmarkers');

Route::get('/singlebookmarker', function () {
    return Inertia::render('SingleBookMarker');
})->middleware(['auth', 'verified'])->name('singlebookmarker');
