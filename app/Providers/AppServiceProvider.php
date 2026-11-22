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
        $request = request();
        $forwardedProto = strtolower(trim(explode(',', (string) $request->header('x-forwarded-proto'), 2)[0]));

        if ($request->isSecure() || $forwardedProto === 'https') {
            URL::forceScheme('https');
        }
    }
}
