<?php

namespace Nuwira\Bandrek;

use Illuminate\Auth\Passwords\PasswordResetServiceProvider as ServiceProvider;
use Nuwira\Bandrek\Password\PasswordBrokerManager;

class BandrekServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     */
    public function register()
    {
        $this->registerPasswordBroker();
        $this->registerBandrekFacade();
    }

    /**
     * Register the password broker instance.
     *
     */
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }

    /**
     * Register Bandrek facade.
     *
     */
    protected function registerBandrekFacade()
    {
        $key = substr($this->app['config']['app.key'], 7);

        $this->app->singleton('bandrek', function ($app) use ($key) {
            return new Bandrek($key);
        });
    }
}
