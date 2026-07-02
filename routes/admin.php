<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Di-load lewat prefix "admin" + middleware auth & role.
| Daftarkan file ini di routes/web.php dengan:
|
|   Route::prefix('admin')->name('admin.')
|       ->middleware(['auth', 'role:admin,editor,author'])
|       ->group(base_path('routes/admin.php'));
*/

Route::get('/', DashboardController::class . '@index')->name('dashboard');

// Berita bisa diakses admin, editor, author (dibatasi lebih detail lewat Policy)
Route::resource('news', NewsController::class)->except(['show']);

// Kategori & komentar: hanya admin/editor (lihat middleware tambahan di bawah)
Route::middleware('role:admin,editor')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::patch('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/spam', [CommentController::class, 'spam'])->name('comments.spam');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Pengaturan tampilan (warna & logo): khusus admin
Route::middleware('role:admin')->group(function () {
    Route::get('settings/appearance', [SettingController::class, 'appearance'])->name('settings.appearance');
    Route::put('settings/appearance', [SettingController::class, 'updateAppearance'])->name('settings.appearance.update');
});
