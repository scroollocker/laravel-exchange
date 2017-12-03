<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AccountFacade extends Facade {
    protected static function getFacadeAccessor() { return 'AccountFacade'; } // most likely you want MyClass here
}