<?php

namespace App\Providers;

use App\Models\Destination;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
        Paginator::defaultView('vendor.pagination.custom');

        // Chỉ share destinations nếu bảng đã tồn tại
        if (Schema::hasTable('destinations')) {
            View::share('destinations', Destination::all());
        }
    }
}
