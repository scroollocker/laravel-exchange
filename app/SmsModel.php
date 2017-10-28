<?php
/**
 * Created by PhpStorm.
 * User: akhaylov
 * Date: 20.10.2017
 * Time: 17:18
 */

namespace App;

use Illuminate\Support\Facades\Mail;

class SmsModel {
    public function printHello() {
        echo 'hello ass';
    }

    public function generatePin() {
        $pin = random_int(10000, 99999);
        return $pin;
    }

    public function sendPin($pin, $user) {

        if (!is_null($user)) {
            $email = $user->email;

            Mail::send('sms.code', array('pin' => $pin), function($message) use ($email) {

                $message->from(env('MAIL_USERNAME'));
                $message->to($email);
                $message->subject('Pin code');
            });

        }
    }
}