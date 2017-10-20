<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class SmsServiceProvider extends ServiceProvider {
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('SmsFacadeClass', function(){
            return new \App\SmsModel;
        });
    }
}