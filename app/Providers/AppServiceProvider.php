<?php

namespace App\Providers;

use App\Models\News;
use App\Policies\NewsPolicy;
use App\View\Composers\FrontLayoutComposer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(News::class, NewsPolicy::class);

        View::composer('front.layouts.app', FrontLayoutComposer::class);
    }
}