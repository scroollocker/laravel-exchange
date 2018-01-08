<?php
/**
 * Created by PhpStorm.
 * User: akhaylov
 * Date: 20.10.2017
 * Time: 17:18
 */

namespace App;

use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use SimpleXMLElement;

class SmsModel {

    /**
     * Адресс API
     * @var string
     */
    private $url = "http://smspro.nikita.kg/api/message";
    /**
     * Логин для доступа к платформе smspro.nikita.kg
     * @var string
     */
    private $login = "demo";
    /**
     * Пароль для доступа к платформе smspro.nikita.kg
     * @var string
     */
    private $password = 'demoPass';
    /**
     * Имя отправителя.
     * Может быть либо текстом на литинице либо цифрами или номером телефона
     * (по согласованию с smspro.nikita.kg)
     * @var string
     */
    private $sender_id = "nikita.kg";
    /**
     * Вкл./Выкл тестовый режим
     * @var boolean
     */
    public $test = false;
    /**
     * Устанавливает параметры для подключения к API
     * @param string $login     Имя пользователя
     * @param string $password  Пароль
     * @param string $sender_id Имя отправителя
     */
    public function setLoginData($login, $password, $sender_id) {
        if (isset($login) && isset($password) && isset($sender_id)) {
            if (!empty($login) && !empty($password) && !empty($sender_id)) {
                $this->login     = $login;
                $this->password  = $password;
                $this->sender_id = $sender_id;
            }
        }
    }
    /**
     * Отправка СМС
     * @param  string $message Текст сообщения
     * @param  array  $numbers Массив с номерами телефонов
     * @return array  Ответ сервера
     */
    public function send($message, $numbers) {
        $xml = $this->generateSendXml($message, $numbers);
        try {
            $result = $this->postContent( $this->url, $xml );
            // Ответ сервера smspro.nikita.kg
            return $result;
        }
        catch(Exception $e) {
            \Log::error($e);
        }
    }
    /**
     * Генерация xml для отправки sms
     * @param  string $message Текст сообщения
     * @param  array  $numbers Массив с номерами телефонов
     * @return sting           xml для запроса
     */
    public function generateSendXml($text, $numbers) {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><message/>');
        $login      = $xml->addChild('login', $this->login);
        $password   = $xml->addChild('pwd', $this->password);
        $id         = $xml->addChild('id', substr(number_format(time() * rand(),0,'',''),0,12) );
        $senderId   = $xml->addChild('sender', $this->sender_id);
        $text       = $xml->addChild('text', $text);
        $phones     = $xml->addChild('phones');
        if ($this->test){
            $test   = $xml->addChild('test', 1);
        }
        if (is_array($numbers)) {
            $length = count($numbers);
            if ($length > 50) {
                throw new Exception("Максимальное число получателей в одном пакете – 50 номеров");
            }
            for ($i = 0; $i < $length; $i++) {
                if (!empty($numbers[$i])) {
                    $phones->addChild('phone', $numbers[$i]);
                }
            }
        }
        else {
            $phones->addChild('phone', $numbers);
        }
        // Header('Content-type: text/xml');
        return $xml->asXML();
    }
    /**
     * Отправка данных на API
     * @param  string $url      Адресс API
     * @param  sting  $postdata  XML для отправки
     * @return array  Результаты запроса
     */
    private function postContent($url, $postdata) {

        $data = \Curl::to($url)
                ->withData($postdata)
                ->post();

        \Log::info($data);
        dd($data);

        return $data;
    }

    public function printHello() {
        echo 'hello ass';
    }

    public function generatePin() {
        $pin = random_int(10000, 99999);
        return $pin;
    }

    public function sendPin($pin, $user) {

        if (!is_null($user)) {

            $login = env('SMS_LOGIN', 'demo');
            $password = env('SMS_PASS', 'demoPass');
            $senderId = env('SMS_SENDER', 'nikita.kg');

            $this->test = env('SMS_TEST', false);


            $this->setLoginData($login, $password, $senderId);


            $view = View::make('sms.code', array('pin' => $pin));
            $content = $view->render();


            $this->send($content, $user->phone);


        }
    }

    public function sendSms($phone, $message) {

            $login = env('SMS_LOGIN', 'demo');
            $password = env('SMS_PASS', 'demoPass');
            $senderId = env('SMS_SENDER', 'nikita.kg');

            $this->test = env('SMS_TEST', false);
            $this->setLoginData($login, $password, $senderId);
            $this->send($message, $phone);
    }


}