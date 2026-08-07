<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/run-link-def456', function () {
    if (request('key') !== 'rahasia-link-portal-2026') {
        abort(403);
    }
    Artisan::call('storage:link');
    return 'Storage link berhasil dibuat: ' . Artisan::output();
});

require base_path('routes/auth.php');
require base_path('routes/front.php');

Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'role:admin,editor,author'])
    ->group(base_path('routes/admin.php'));