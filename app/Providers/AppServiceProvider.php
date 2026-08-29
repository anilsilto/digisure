<?php

namespace App\Providers;

use App\Domain\Insurer\QuoteProviderManager;
use App\Models\User;
use App\Notifications\Sms\LogSmsSender;
use App\Notifications\Sms\NetgsmSmsSender;
use App\Notifications\Sms\SmsSender;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(QuoteProviderManager::class);

        $this->app->bind(SmsSender::class, fn () => config('digisure.sms.driver') === 'netgsm'
            ? new NetgsmSmsSender()
            : new LogSmsSender());
    }

    public function boot(): void
    {
        Gate::define('panel.admin', fn (User $user) => $user->isAdmin());
    }
}
