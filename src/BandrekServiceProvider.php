<?php

namespace Nuwira\Bandrek;

use Illuminate\Auth\Passwords\PasswordResetServiceProvider as ServiceProvider;
use Nuwira\Bandrek\Password\PasswordBrokerManager;

class BandrekServiceProvider extends ServiceProvider
{
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }
}
