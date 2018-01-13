<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'tb_offer';
    protected  $primaryKey = 'offer_id';

    public function state() {
        return $this->belongsTo('App\DealState', 'state_id', 'deal_state_id');
    }

    public function origin() {
        return $this->belongsTo('App\Invoice', 'declare_id', 'declare_id');
    }

    public function detail() {
        return $this->belongsTo('App\Invoice', 'details_id', 'declare_id');
    }
}
