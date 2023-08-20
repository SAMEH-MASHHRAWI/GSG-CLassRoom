<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Classwork;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        Relation::enforceMorphMap([
            'classwork'=>Classwork::class,
            'post'=>Post::class,
        ]);
    }
}
