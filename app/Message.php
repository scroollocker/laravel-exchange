<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $dates = ['date_send'];

    public function chat() {
        $this->belongsTo('App\Message', 'chat_id', 'chat_id');
    }
}
