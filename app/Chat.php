<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{

    protected $dates = ['date'];
    protected $primaryKey = 'chat_id';


    public function messages() {
        return $this->hasMany('App\Message', 'chat_id', 'chat_id');
    }

    public function author() {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    public function recipient() {
        return $this->belongsTo('App\User', 'recipient_id', 'id');
    }

    public static function createChat($author, $invoice_id, $message = null) {
        $chat = new Chat();

        $invoice = Invoice::where('declare_id', $invoice_id)->first();

        $chat->author_id = $author;
        $chat->invoice_id = $invoice_id;
        $chat->date = Carbon::now();
        $chat->recipient_id = $invoice->user_id;
        $chat->save();

        if (!is_null($message)) {
            $chatMessage = new Message();

            $chatMessage->chat_id = $chat->chat_id;
            $chatMessage->message = $message;
            $chatMessage->author_id = $author;
            $chatMessage->date_send = Carbon::now();

            $chatMessage->save();
        }

    }

}
