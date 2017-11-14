<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dotenv\Validator;
use Exception;
use Illuminate\Http\Request;
use App\Chat;

class ChatController extends Controller
{
    public function test() {

    }

    public function getMessages(Request $request) {
        $invoiceId = $request->invoice_id;

        try {
            if ($invoiceId == null or $invoiceId == '') {
                throw new Exception('Неверные данные');
            }

            /*$user = \Auth::user();
            if (is_null($user)) {
                throw new Exception('Вы не авторизованы');
            }*/

            $messages = Chat::where('invoice_id', $invoiceId)
                            ->get();

            return response()->json(array(
                'status' => true,
                'data' => $messages->toArray()
            ));

        }
        catch(Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function checkNew(Request $request) {
        $count = $request->count;
        $invoiceId = $request->invoice_id;

        try {
            if ($count == null or $count == '') {
                throw new Exception('Неверные данные');
            }
            if ($invoiceId == null or $invoiceId == '') {
                throw new Exception('Неверные данные');
            }

            /*$user = \Auth::user();
            if (is_null($user)) {
                throw new Exception('Вы не авторизованы');
            }*/

            $messages = Chat::select('count(*) as m_count')
                ->where('invoice_id', $invoiceId)
                ->where('m_count', '>', $invoiceId)
                ->get();


            $needUpdate = false;
            if (count($messages->toArray()) > 0) {
                $needUpdate = true;
            }

            return response()->json(array(
                'status' => true,
                'needUpdate' => $needUpdate
            ));

        }
        catch(Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function send(Request $request) {

        $validation_messages = array(
            'invoice_id.required' => 'Неверный запрос',
            'message.required' => 'Не заполнено сообщение',
            'message.max' => 'Длина сообщения слишком велика'
        );

        $validation_rules = array(
            'invoice_id' => array(
                'required'
            ),
            'message' => array(
                'required',
                'max:1000'
            )
        );

        try {
            $validator = Validator::make($request->all(), $validation_rules, $validation_messages);

            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors->all() as $error) {
                    $errMsg .= $error;
                }

                throw new Exception($errMsg);
            }

            $message = new Chat();

            $message->invoice_id = $request->invoice_id;
            $message->message = $message;
            $message->send_date = Carbon::now();

            $message->save();

            return response()->json(array(
                'status'=> true
            ));

        } catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

}
