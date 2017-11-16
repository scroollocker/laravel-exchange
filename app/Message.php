<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $dates = ['date_send'];

    public function chat() {
        return $this->belongsTo('App\Chat', 'chat_id', 'chat_id');
    }

    public function author() {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }
}
