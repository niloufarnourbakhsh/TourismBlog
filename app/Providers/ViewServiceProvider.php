<?php

namespace App\Providers;

use App\View\Composer\CategoriesClass;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['Admin.*','Users.gallery'],CategoriesClass::class);
    }
}
