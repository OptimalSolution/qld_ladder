<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        /**
         * Change default string length.
         *
         * MariaDB 10.5 allows index keys to be 3072 chars.
         * MySQL 8.0 appears to be allowing only 1000 chars.
         */
        Schema::defaultStringLength(125);

        /**
         * Register Event Listeners.
         */
        $this->registerEventListeners();

        /**
         * Implicitly grant "Super Admin" role all permissions
         * This works in the app by using gate-related functions like auth()->user->can() and @can().
         */
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super admin') ? true : null;
        });
        // Or if the APP_URL contains https, force the scheme to https
        if (strpos(config('app.url'), 'https://') !== false) {
            URL::forceScheme('https');
        }
    }

    public function registerEventListeners()
    {

    }
}
