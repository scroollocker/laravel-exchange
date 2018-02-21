<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    protected $table = 'tb_msg';
    protected $primaryKey = 'msg_id';

    public static function checkSended($id) {
        DB::select('call send_msg(?);', array($id));
    }

}
