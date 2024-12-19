<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        view()->composer('*', function ($view) {

            $unreadNotifications = 0;

            if (auth()->check()) {
                $unreadNotifications = auth()->user()->notifications()->wherePivot('is_read', 0)->count();
            }

            $view->with('unreadNotifications', $unreadNotifications);

        });

    }
}
