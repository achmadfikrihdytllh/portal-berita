<?php

use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\NewsController;
use App\Http\Controllers\Front\NewsletterController;
use App\Http\Controllers\Front\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\FocusController;
use App\Http\Controllers\Front\EpaperController;
use App\Http\Controllers\Front\GalleryController;
use App\Http\Controllers\Front\KanalController;

/*
|--------------------------------------------------------------------------
| Front (Public) Routes
|--------------------------------------------------------------------------
| Daftarkan file ini di routes/web.php dengan:
|
|   require base_path('routes/front.php');
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::post('/berita/{news:slug}/komentar', [NewsController::class, 'storeComment'])
    ->name('news.comment')
    ->middleware('throttle:10,1'); 
Route::get('/fokus', [FocusController::class, 'index'])->name('focuses.index');
Route::get('/fokus/{focus:slug}', [FocusController::class, 'show'])->name('focuses.show');

Route::get('/ekoran', [EpaperController::class, 'index'])->name('epapers.index');

Route::get('/foto', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/foto/{gallery:slug}', [GalleryController::class, 'show'])->name('galleries.show');

Route::get('/kanal', [KanalController::class, 'index'])->name('kanal.index');
Route::get('/kategori/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/cari', [SearchController::class, 'index'])->name('search');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe')
    ->middleware('throttle:5,1');
