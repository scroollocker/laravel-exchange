<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class ApiServiceProvider extends ServiceProvider {
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('ApiFacadeClass', function(){
            return new \App\ApiModel;
        });
    }
}