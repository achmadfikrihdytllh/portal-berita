<?php

use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\NewsController;
use App\Http\Controllers\Front\NewsletterController;
use App\Http\Controllers\Front\SearchController;
use Illuminate\Support\Facades\Route;

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
    ->middleware('throttle:10,1'); // batasi spam komentar

Route::get('/kategori/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/cari', [SearchController::class, 'index'])->name('search');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe')
    ->middleware('throttle:5,1');
