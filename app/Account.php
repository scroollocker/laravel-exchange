<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'tb_acc';
    protected $primaryKey = 'acc_id';

    public function state() {
        return $this->belongsTo('App\AccountState', 'state_id', 'acc_state_id');
    }

    public function currency() {
        return $this->belongsTo('App\Currency', 'cur_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    protected $casts = [
        'for_loan_n' => 'boolean',
        'for_deal_n' => 'boolean'
    ];
}
