<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/run-migrate-xyz123', function () {
    if (request('key') !== 'rahasia-portal-berita-2026') {
        abort(403);
    }
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration berhasil dijalankan: ' . Artisan::output();
});

require base_path('routes/auth.php');
require base_path('routes/front.php');

Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'role:admin,editor,author'])
    ->group(base_path('routes/admin.php'));