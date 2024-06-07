<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Models\Bookmark;
use App\Models\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
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
Route::get('/current-language', [LanguageController::class, 'currentLanguage'])->name('language.current');

require __DIR__ . '/auth.php';

// ROUTES FRONTEND PRUEBAS
Route::get('/prueba', function () {
    return Inertia::render('Prueba');
})->middleware(['auth', 'verified'])->name('prueba');


// BUSQUEDA AVANZADA
Route::get('/searchadvanced', function (Request $request) {
    $type = $request->query('type');
    return Inertia::render('SearchAdvanced',[
        'type' => $type
    ]);
})->middleware(['auth', 'verified'])->name('searchadvanced');


// INDEX
Route::get('/bookmarks', function () {
    $user = Auth::user();
    // $bookmarkController = new BookmarkController();
    // $bookmarkResource = $bookmarkController->index();

    return Inertia::render('Bookmarks', [
        'user' => $user,
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


// Collections 

//  INDEX
Route::get('/collections', function () {
    return Inertia::render('Collections');
})->middleware(['auth', 'verified'])->name('collections');

// SHOW 
Route::get('/collections/{id}', function ($id) {

    return Inertia::render('ShowCollections', [
        'collection_id' => $id
    ]);
})->middleware(['auth', 'verified'])->name('showcollection');

// CREATE
Route::get('/createcollection', function() {
    return Inertia::render('CreateCollections');

})->middleware(['auth', 'verified'])->name('createcollection');