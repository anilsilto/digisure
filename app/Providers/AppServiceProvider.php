<?php

namespace App\Providers;

use App\Domain\Insurer\QuoteProviderManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(QuoteProviderManager::class);
    }

    public function boot(): void
    {
        //
    }
}
