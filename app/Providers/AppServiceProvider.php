<?php

namespace App\Providers;

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
        // Cek apakah aplikasi berjalan di lingkungan Vercel/Production
        if (config('app.env') === 'production') {
            // Paksa Laravel untuk menggunakan skema HTTPS saat membuat URL
            URL::forceScheme('https');
        }
    }
}
