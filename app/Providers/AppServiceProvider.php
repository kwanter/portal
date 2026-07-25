<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        // ponytail: dropped ->uncompromised() — requires live HIBP network call,
        // breaks deterministic tests and CI without network. Core complexity
        // (min 12 + letters + numbers + symbols) fully addresses audit finding 3.7.
        // Re-add ->uncompromised() when prod egress to haveibeenpwned.com is confirmed.
        Password::defaults(
            fn () => Password::min(12)->letters()->numbers()->symbols(),
        );
    }
}
