<?php

namespace App\Http\Controllers;

use App\Message;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Chat;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{

    public function getInvoiceChats(Request $request) {
        $invoiceId = $request->invoice_id;

        try {
            if ($invoiceId == null or $invoiceId == '') {
                throw new Exception('Неверный запрос');
            }

            $user = \Auth::user();
            if (is_null($user)) {
                throw new Exception('Вы не авторизованы');
            }

            $chats = Chat::where('invoice_id', $invoiceId)
                ->where(function ($query) use ($user) {
                    $query->orWhere('author_id', $user->id);
                    $query->orWhere('recipient_id', $user->id);
                })
                ->get();

            $chats->load('author');
            $chats->load('recipient');

            return response()->json(array(
                'status' => true,
                'chats' => $chats->toArray()
            ));
        }
        catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function getMessages(Request $request) {
        $chatId = $request->chat_id;

        try {
            if ($chatId == null or $chatId == '') {
                throw new Exception('Неверные данные');
            }

            $user = \Auth::user();
            if (is_null($user)) {
                throw new Exception('Вы не авторизованы');
            }

            $chat = Chat::where('chat_id', $chatId)->first();

            if ($chat->author_id != $user->id and $chat->recipient_id != $user->id ) {
                throw new Exception('У вас нет доступа к просмотру этого чата.');
            }

            $messages = Message::where('chat_id', $chatId)
                            ->get();

            $messages->load('chat');
            $messages->load('author');

            return response()->json(array(
                'status' => true,
                'messages' => $messages->toArray()
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
        $chatId = $request->chat_id;

        try {
            if ($count == null or $count == '') {
                throw new Exception('Неверные данные');
            }
            if ($chatId == null or $chatId == '') {
                throw new Exception('Неверные данные');
            }

            $user = \Auth::user();
            if (is_null($user)) {
                throw new Exception('Вы не авторизованы');
            }

            $chat = Chat::where('chat_id', $chatId)->first();

            if ($chat->author_id != $user->id and $chat->recipient_id != $user->id ) {
                throw new Exception('У вас нет доступа к просмотру этого чата.');
            }

            $messages = Message::select('count(*) as m_count')
                ->where('chat_id', $chatId)
                ->where('m_count', '>', $count)
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
            'chat_id.required' => 'Неверный запрос',
            'message.required' => 'Не заполнено сообщение',
            'message.max' => 'Длина сообщения слишком велика'
        );

        $validation_rules = array(
            'chat_id' => array(
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

            $user = \Auth::user();
            if (is_null($user)) {
                throw new Exception('Вы не авторизованы');
            }

            $chat = Chat::where('chat_id', $request->chat_id)->first();

            if ($chat->author_id != $user->id and $chat->recipient_id != $user->id ) {
                throw new Exception('У вас нет доступа к просмотру этого чата.');
            }

            $message = new Message();

            $message->chat_id = $request->chat_id;
            $message->message = $request->message;
            $message->author_id = $user->id;
            $message->date_send = Carbon::now();

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
