<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{

    public function invoiceList()
    {
        return view('invoices.invoices-list');
    }

    public function invoiceAdd()
    {
        return view('invoices.invoices-add');
    }

    public function chatBase()
    {
        return view('chat.chat-base');
    }

    public function getCurrences()
    {
        try {
            $currences = \DB::table('currencies')
                ->select('id', 'cur_name')
                ->where('cur_enable', '1')
                ->get();

            return response()->json(array(
                'status' => true,
                'currences' => $currences->toArray()
            ));
        } catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }

    public function getAccounts(Request $request)
    {

        $messages = array(
            'cur_1_id.required' => 'Неверный запрос',
            'cur_1_id.integer' => 'Неверный формат запроса',
            'cur_2_id.required' => 'Неверный запрос',
            'cur_2_id.integer' => 'Невернй формат запроса'
        );

        $rules = array(
            'cur_1_id' => array(
                'required', 'integer'
            ),
            'cur_2_id' => array(
                'required', 'integer'
            )
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        try {
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            $user = \Auth::user();

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            $account1 = \DB::table('tb_acc')
                ->select('name_v as acc_name', 'num_v as acc_num', 'acc_id as id')
                ->where('user_id', $user->id)
                ->where('for_deal_n', '1')
                ->where('cur_id', $request->cur_1_id)
                ->get();

            $account2 = \DB::table('tb_acc')
                ->select('name_v as acc_name', 'num_v as acc_num', 'acc_id as id')
                ->where('user_id', $user->id)
                ->where('for_deal_n', '1')
                ->where('cur_id', $request->cur_2_id)
                ->get();

            return response()->json(array(
                'status' => true,
                'accounts_cur_1' => $account1->toArray(),
                'accounts_cur_2' => $account2->toArray()
            ));

        } catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }

    public function getPartners() {
        try {
            $user = Auth::user();

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            $partners = \DB::table('tb_user_list as ul')
                            ->select('ul.user_list_id AS id', 'ul.user_id', 'ul.partner_id', 'p.name', 'p.email', 'ul.state_n')
                            ->where('ul.user_id', $user->id)
                            ->join('users as p', 'ul.partner_id', '=', 'p.id')
                            ->get();

            return response()->json(array(
                'status' => true,
                'autoconfirm' => $user->autoconfirm,
                'partners' => $partners->toArray()
            ));

        } catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

}
