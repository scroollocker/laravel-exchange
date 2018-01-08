<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'tb_declare';
    protected $primaryKey = 'declare_id';

    protected $dateFormat = 'Y-m-d';

    protected $dates = [
        'end_dt',
        'created_dt'
    ];

    public function currency_sell() {
        return $this->belongsTo('App\Currency', 'cur_sell_id', 'id');
    }

    public function currency_buy() {
        return $this->belongsTo('App\Currency', 'cur_buy_id', 'id');
    }

    public function state() {
        return $this->belongsTo('App\DealState', 'state_id', 'deal_state_id');
    }

    public function acc_dt() {
        return $this->belongsTo('App\Account', 'acc_dt_id', 'acc_id');
    }

    public function acc_ct() {
        return $this->belongsTo('App\Account', 'acc_ct_id', 'acc_id');
    }

    public function type() {
        return $this->belongsTo('App\DeclareType', 'type_id', 'declare_type_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    protected $casts = [
        'private_n' => 'boolean',
        'approve_auto_n' => 'boolean'
    ];
}
