<?php

namespace App\Providers;

use App\Services\AI\AIManager;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AIManager::class, function ($app) {

            $providers = [];

            foreach (config('ai.providers') as $providerConfig) {
                $providers[] = $app->make($providerConfig['driver']);
            }

            return new AIManager($providers);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
