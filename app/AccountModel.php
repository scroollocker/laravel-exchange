<?php

namespace App;

use Illuminate\Support\Facades\DB;

class AccountModel
{

    public function getAccounts($userId) {
        $sql = 'SELECT a.acc_id, a.num_v, a.name_v as account_name, c.cur_name, a.saldo_nd, a.saldo_limit_nd, s.name_v as account_status, a.for_deal_n
                FROM tb_acc a
                INNER JOIN tb_acc_state s ON s.acc_state_id = a.state_id
                INNER JOIN currencies as c ON a.cur_id = c.id
                WHERE s.code_v = \'WORK\' AND a.for_loan_n = 0 AND a.user_id = ? ORDER BY a.saldo_nd DESC';

        $accounts = DB::select($sql, array($userId));

        return $accounts;
    }

    public function setAccountState($account_id, $account_state) {
        $sql = 'call upd_acc_for_deal(?,?);';

        DB::select($sql, array($account_id, $account_state));
    }
}
