<?php
/**
 * Created by PhpStorm.
 * User: scroollocker
 * Date: 13.01.18
 * Time: 23:37
 */

namespace App;


class ApiModel {
    public function test() {
        echo 'Hello';
    }

    public function execute($name, $params) {
        $url = env('API_URL', '');
        if (empty($url)) {
            return null;
        }

        $url .= $name . "=" . json_encode($params);

        \Log::info('Execute api');
        \Log::info($url);

        $result = \Curl::to($url)->get();

        $result = json_decode($result, true);
        \Log::info($result);

        return $result;
    }
}