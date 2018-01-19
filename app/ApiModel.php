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

    private function parseResult($result) {
        $response_object = array(
            'status' => false,
            'message' => ''
        );

        if (!$result or !isset($result['response'])) {
            return $response_object;
        }

        $result = $result['response'];

        if (isset($result['errorno'])) {
            $response_object = array(
                'status' => false,
                'message' => $result['error']
            );

            return $response_object;
        }

        $response_object = array(
            'status' => true,
            'response' => $result
        );

        \Log::info($response_object);

        return $response_object;
    }

    public function execute($name, $params) {
        $url = env('API_URL', '');
        if (empty($url)) {
            return null;
        }

        $url .= $name . "=" . json_encode($params);

        \Log::info('Execute api');
        \Log::info($url);

        $result = \Curl::to($url)
            //->withProxy('10.230.143.9', 3128, 'https://')
            ->get();

        $result = json_decode($result, true);
        \Log::info($result);

        return $this->parseResult($result);
    }
}