<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AccountProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AccountFacade', function() {
            return new \App\AccountModel;
        });
    }
}
