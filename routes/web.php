<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Models\Bookmark;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
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


// INDEX
Route::get('/bookmarks', function () {
    $user = Auth::user();
    $token = $user->getRememberToken();
    $bookmarkController = new BookmarkController();
    $bookmarkResource = $bookmarkController->index();

    return Inertia::render('Bookmarks', [
        'user' => $user,
        'token' => $token
    ]);
})->middleware(['auth', 'verified'])->name('bookmarks');


//
Route::get('/bookmarks/{bookmark}', function (Bookmark $bookmark) {
    $user = Auth::user();
    $token = $user->getRememberToken();
    $bookmarkController = new BookmarkController();
    $bookmarkResource = $bookmarkController->show($bookmark);

    return Inertia::render('SingleBookmark', [
        'bookmark' => $bookmarkResource,
        'token' => $token
    ]);
})->middleware(['auth', 'verified'])->name('singlebookmarker');

Route::get('/createbookmark', function () {
    return Inertia::render('CreateBookmark');
})->middleware(['auth', 'verified'])->name('createbookmark');
