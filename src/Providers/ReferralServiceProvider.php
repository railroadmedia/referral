<?php

namespace Railroad\Referral\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class ReferralServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->publishes(
            [
                __DIR__ . '/../../config/referral.php' => config_path('referral.php'),
            ]
        );

        if (config('referral.data_mode') == 'host') {
            $this->loadMigrationsFrom(__DIR__.'/../../migrations');
        }

        //load package routes file
        $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
