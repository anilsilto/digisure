<?php

namespace App\Providers;

use App\Domain\Insurer\QuoteProviderManager;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(QuoteProviderManager::class);
    }

    public function boot(): void
    {
        Gate::define('panel.admin', fn (User $user) => $user->isAdmin());
    }
}
