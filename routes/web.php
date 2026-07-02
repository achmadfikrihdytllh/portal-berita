<?php

use Illuminate\Support\Facades\Route;

require base_path('routes/auth.php');
require base_path('routes/front.php');

Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'role:admin,editor,author'])
    ->group(base_path('routes/admin.php'));