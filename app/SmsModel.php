<?php
/**
 * Created by PhpStorm.
 * User: akhaylov
 * Date: 20.10.2017
 * Time: 17:18
 */

namespace App;


class SmsModel {
    public function printHello() {
        echo 'hello ass';
    }

    public function generatePin() {
        $pin = random_int(10000, 99999);
        return $pin;
    }
}