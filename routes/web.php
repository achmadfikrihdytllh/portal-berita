<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/run-fresh-jkl345', function () {
    if (request('key') !== 'rahasia-fresh-portal-2026') {
        abort(403);
    }
    Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
    return 'Fresh migrate + seed berhasil dijalankan: ' . Artisan::output();
});

require base_path('routes/auth.php');
require base_path('routes/front.php');

Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'role:admin,editor,author'])
    ->group(base_path('routes/admin.php'));