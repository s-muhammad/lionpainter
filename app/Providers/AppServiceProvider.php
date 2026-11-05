<?php

namespace App\Providers;

use App\Models\Service;
use Illuminate\Pagination\Paginator;
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
        Paginator::defaultView('vendor.pagination.admin');
        Paginator::defaultSimpleView('vendor.pagination.admin');

        View::composer(['components.layout', 'components.site-footer', 'components.quote-cta'], function ($view) {
            $view->with('navServices', Service::active()->ordered()->get());
        });
    }
}
