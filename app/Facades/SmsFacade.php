<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SmsFacade extends Facade {
    protected static function getFacadeAccessor() { return 'SmsFacadeClass'; } // most likely you want MyClass here
}