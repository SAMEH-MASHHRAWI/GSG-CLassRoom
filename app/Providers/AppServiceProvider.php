<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Models\Classwork;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
        // ResourceCollection::withoutWrapping();

        App::setlocale('ar');

        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.bootstrap-5');

        Relation::enforceMorphMap([
            'post'=>Post::class,
            'classwork' => Classwork::class,
            'user'=>User::class,

        ]);
    }
}
