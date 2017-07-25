<?php

namespace Nuwira\Gembok;

use Illuminate\Auth\Passwords\PasswordResetServiceProvider as ServiceProvider;

class GembokServiceProvider extends ServiceProvider
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
