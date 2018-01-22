<?php

namespace App\Http\Controllers;

use App\Account;
use App\Offer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

            /*$account1 = \DB::table('tb_acc')
                ->select('name_v as acc_name', 'num_v as acc_num', 'acc_id as id')
                ->where('user_id', $user->id)
                ->where('for_deal_n', '1')
                ->where('cur_id', $request->cur_1_id)
                ->get();
            */
            $account1 = Account::selectRaw('name_v as acc_name, num_v as acc_num, acc_id as id, (saldo_nd - saldo_limit_nd) as saldo')
                ->where('user_id', $user->id)
                ->where('for_deal_n', '1')
                ->where('cur_id', $request->cur_1_id)

                ->get();

            /*$account2 = \DB::table('tb_acc')
                ->select('name_v as acc_name', 'num_v as acc_num', 'acc_id as id')
                ->where('user_id', $user->id)
                ->where('for_deal_n', '1')
                ->where('cur_id', $request->cur_2_id)
                ->get();
            */

            $account2 = Account::selectRaw('name_v as acc_name, num_v as acc_num, acc_id as id, (saldo_nd - saldo_limit_nd) as saldo')
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

    private function _getInvoiceById($id, $user_id) {
        $invoice = \DB::table('tb_declare as d')
            ->select('d.approve_auto_n as autoconfirm',
                'd.course_nd as cur_curs',
                'd.declare_id AS id',
                'd.sum_sell_nd as cur_sum',
                'd.sum_buy_nd as final_sum',
                'd.end_dt as endDate',
                'tbs.deal_state_id as \'state.id\'',
                'tbs.code_v as \'state.code\'',
                'tbs.name_v as \'state.name\'',
                'ac1.acc_id as \'acc_1.id\'',
                'ac1.num_v as \'acc_1.acc_num\'',
                'ac1.name_v as \'acc_1.acc_name\'',
                'ac2.acc_id as \'acc_2.id\'',
                'ac2.num_v as \'acc_2.acc_num\'',
                'ac2.name_v as \'acc_2.acc_name\'',
                'cu1.id as \'cur_1.id\'',
                'cu1.cur_name as \'cur_1.cur_name\'',
                'cu2.id as \'cur_2.id\'',
                'cu2.cur_name as \'cur_2.cur_name\'')
            ->join('tb_acc AS ac1', 'd.acc_ct_id', '=', 'ac1.acc_id')
            ->join('tb_acc AS ac2', 'd.acc_dt_id', '=', 'ac2.acc_id')
            ->join('currencies AS cu1', 'd.cur_buy_id', '=', 'cu1.id')
            ->join('currencies AS cu2', 'd.cur_sell_id', '=', 'cu2.id')
            ->join('tb_deal_state AS tbs', 'd.state_id', '=', 'tbs.deal_state_id')
            ->where('d.declare_id', $id)
            ->where('d.user_id', $user_id)
            ->first();

        return $invoice;
    }

    public function getInvoiceById(Request $request) {
        $messages = array (
            'invoice_id.required' => 'Неверный запрос',
            'invoice_id.integer' => 'Неверный формат'
        );

        $rules = array (
            'invoice_id' => array(
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

            $user = Auth::user();

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            /*$invoice = \DB::table('tb_declare as d')
                          ->select('d.approve_auto_n as autoconfirm',
                                   'd.course_nd as cur_curs',
                                   'd.declare_id AS id',
                                   'd.sum_sell_nd as cur_sum',
                                   'd.sum_buy_nd as final_sum',
                                   'd.end_dt as endDate',
                                   'ac1.acc_id as \'acc_1.id\'',
                                   'ac1.num_v as \'acc_1.acc_num\'',
                                   'ac1.name_v as \'acc_1.acc_name\'',
                                   'ac2.acc_id as \'acc_2.id\'',
                                   'ac2.num_v as \'acc_2.acc_num\'',
                                   'ac2.name_v as \'acc_2.acc_name\'',
                                   'cu1.id as \'cur_1.id\'',
                                   'cu1.cur_name as \'cur_1.cur_name\'',
                                   'cu2.id as \'cur_2.id\'',
                                   'cu2.cur_name as \'cur_2.cur_name\'')
                          ->join('tb_acc AS ac1', 'd.acc_ct_id', '=', 'ac1.acc_id')
                          ->join('tb_acc AS ac2', 'd.acc_dt_id', '=', 'ac2.acc_id')
                          ->join('currencies AS cu1', 'd.cur_buy_id', '=', 'cu1.id')
                          ->join('currencies AS cu2', 'd.cur_sell_id', '=', 'cu2.id')
                          ->where('d.declare_id', $request->invoice_id)
                          ->where('d.user_id', $user->id)
                          ->first();
            */
            $invoice = $this->_getInvoiceById($request->invoice_id, $user->id);
            $status = false;

            if (!is_null($invoice)) {
                $status = true;
                $invoice->type = '1';
            }

            return response()->json(array(
                'status' => $status,
                'invoice' => $invoice
            ));
        }
        catch (Exception $ex) {
            \Log::error('Get invoice Error');
            \Log::error($ex);
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function saveInvoice(Request $request) {
        try {
            $messages = array(
                'acc_1.required' => 'Неверный запрос',
                'acc_2.required' => 'Неверный запрос',
                'autoconfirm.required' => 'Неверный запрос',
                'cur_1.required' => 'Неверный запрос',
                'cur_2.required' => 'Неверный запрос',
                'sum_1.required' => 'Неверный запрос',
                'sum_2.required' => 'Неверный запрос',
                'type.required' => 'Неверный запрос',
                'endDate.required' => 'Неверный запрос',
                'id.required' => 'Неверный запрос',
                'acc_1.integer' => 'Неверный запрос',
                'acc_2.integer' => 'Неверный запрос',
                'autoconfirm.regex' => 'Неверный запрос',
                'cur_1.integer' => 'Неверный запрос',
                'cur_2.integer' => 'Неверный запрос',
                'sum_1.numeric' => 'Неверный запрос',
                'sum_2.numeric' => 'Неверный запрос',
                'type.regex' => 'Неверный запрос',
                'endDate.date' => 'Неверная дата окончания сделки',
                'id.integer' => 'Неверный запрос',
            );

            $rules = array(
                'acc_1' => array(
                    'required', 'integer'
                ),
                'acc_2' => array(
                    'required', 'integer'
                ),
                'autoconfirm' => array(
                    'required', 'regex:/^[0|1]$/'
                ),
                'cur_1' => array(
                    'required', 'integer'
                ),
                'cur_2' => array(
                    'required', 'integer'
                ),
                'sum_1' => array(
                    'required', 'numeric'
                ),
                'sum_2' => array(
                    'required', 'numeric'
                ),
                'type' => array(
                    'required', 'regex:/^[1|2]$/'
                ),
                'endDate' => array(
                    'required', 'date'
                )
            );

            if (!$request->action) {
                throw new Exception('Неверный запрос.');
            }

            if ($request->action == 'edit') {
                $rules['id'] = array(
                    'required', 'integer'
                );
            }

            $validator = Validator::make($request->all(), $rules, $messages);

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

            $partners = $request->partners;

            $declare_id = ($request->action == 'edit') ? $request->id : null;
            $isPrivate = ($partners) ? 0 : 0;

            $params = array(
                $user->id,
                $request->type,
                $request->sum_1,
                $request->cur_1,
                $request->cur_2,
                $request->sum_2,
                $declare_id,
                $request->acc_1,
                $request->acc_2,
                $request->endDate,
                '',
                $isPrivate,
                $request->autoconfirm
            );

            $result = DB::select('select get_enable_saldo(?) as saldo_sum;', array($request->acc_1));

            if (!$result and !isset($result[0])) {
                throw new Exception('Не найден счет для снятия средств');
            }

            \Log::info('saldo');
            \Log::info($result);

            $saldo = (int)$result[0]->saldo_sum;
            $sum = $request->sum_1;

            if ($saldo < $sum) {
                throw new Exception('У вас нет доступных средств на счете');
            }

            DB::beginTransaction();
            $result = DB::select('select create_declare_2(?,?,?,?,?,?,?,?,?,?,?,?,?) as declare_id;', $params);

            $dec_id = $result[0]->declare_id;

            \Log::info($result);

            if (!$result and !isset($result[0])) {
                DB::rollBack();
                throw new Exception('Произошла системная ошибка создания Заявки');
            }
            else {

                if ($request->autoconfirm == '0' && $partners) {
                    foreach ($partners as $partner) {
                        DB::select('call merge_declare_step_3(?,?,?);', array($result[0]->declare_id, $partner['id'], $partner['state_n']));
                    }
                }
            }

            $params = array(
                'Acc' => $request->acc_1,
                'Sum' => $request->sum_1
            );

            /* TODO: Add confirm */
            $result = \Api::execute('lockAccount', $params);

            if ($result['status'] == false) {
                DB::rollBack();
                throw new \Exception('Ошибка АБС: '.$result['message']);
            }

            DB::commit();

            return response()->json(array(
                'status' => true,
                'declare_id' => $dec_id
            ));
        }
        catch (Exception $ex) {
            \Log::error('Save invoice Error');
            \Log::error($ex);
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }


    }

    public function getInvoiceList(Request $request) {

        try {

            $user = Auth::user();

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            $invoices = \DB::table('tb_declare as d')
                ->select('d.approve_auto_n as autoconfirm',
                    'd.course_nd as cur_curs',
                    'd.declare_id AS id',
                    'd.step_n as step',
                    'd.created_dt AS created_date',
                    'd.sum_sell_nd as cur_sum',
                    'd.sum_buy_nd as final_sum',
                    'd.end_dt as endDate',
                    'tbs.deal_state_id as \'state.id\'',
                    'tbs.code_v as \'state.code\'',
                    'tbs.name_v as \'state.name\'',
                    'ac1.acc_id as \'acc_1.id\'',
                    'ac1.num_v as \'acc_1.acc_num\'',
                    'ac1.name_v as \'acc_1.acc_name\'',
                    'ac2.acc_id as \'acc_2.id\'',
                    'ac2.num_v as \'acc_2.acc_num\'',
                    'ac2.name_v as \'acc_2.acc_name\'',
                    'cu1.id as \'cur_1.id\'',
                    'cu1.cur_name as \'cur_1.cur_name\'',
                    'cu2.id as \'cur_2.id\'',
                    'cu2.cur_name as \'cur_2.cur_name\'')
                ->join('tb_acc AS ac1', 'd.acc_ct_id', '=', 'ac1.acc_id')
                ->join('tb_acc AS ac2', 'd.acc_dt_id', '=', 'ac2.acc_id')
                ->join('currencies AS cu1', 'd.cur_buy_id', '=', 'cu1.id')
                ->join('currencies AS cu2', 'd.cur_sell_id', '=', 'cu2.id')
                ->join('tb_deal_state AS tbs', 'd.state_id', '=', 'tbs.deal_state_id')
                ->join('tb_declare_type AS tdt', 'd.type_id', '=', 'tdt.declare_type_id')
                ->where('d.user_id', $user->id)
                ->where('tdt.code_v', 'DECLARE')
                ->get();

            return response()->json(array(
                'status' => true,
                'invoices' => $invoices
            ));
        }
        catch (Exception $ex) {
            \Log::error('Get invoice Error');
            \Log::error($ex);
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function removeInvoice(Request $request) {
        $rules = array(
            'id' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'id.required' => 'Неверный запрос',
            'id.integer' => 'Неверный запрос',
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

            $user = Auth::user();
            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            DB::table('tb_declare')
                ->where('declare_id', $request->id)
                ->where('user_id', $user->id)
                ->delete();

            return response()->json(array(
                'status' => true
            ));

        }
        catch (Exception $ex) {
            \Log::error('Remove invoice error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function getOffersInvoice(Request $request) {
        return view('invoices.offers-list');
    }

    public function getOffersDetail() {
        return view('invoices.offer-detail');
    }

    public function getOffersByInvoice(Request $request) {
        $rules = array(
            'invoice_id' => array(
                'integer', 'required'
            )
        );

        $messages = array(
            'invoice_id.integer' => 'Неверный запрос',
            'invoice_id.required' => 'Неверный запрос'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        $user = \Auth::user();

        try {
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            $offers = DB::table('tb_offer AS o')
                ->select(
                    'o.offer_id AS id',
                    'o.details_id AS detail_id',
                    'o.declare_id AS declare_id',
                    'td.sum_buy_nd AS sum_1',
                    'td.sum_sell_nd AS sum_2',
                    'td.created_dt as created_date',
                    'td.end_dt as endDate',
                    'td.course_nd as course',
                    'tds.name_v as offer_state',
                    'cur1.id as \'cur_1.id\'',
                    'cur1.cur_name as \'cur_1.name\'',
                    'cur2.id as \'cur_2.id\'',
                    'cur2.cur_name as \'cur_2.name\'')
                ->join('tb_declare AS td', 'td.declare_id', '=', 'o.details_id')
                ->join('tb_declare AS tdo', 'tdo.declare_id', '=', 'o.declare_id')
                ->join('currencies AS cur1', 'cur1.id', '=', 'td.cur_sell_id')
                ->join('currencies AS cur2', 'cur2.id', '=', 'td.cur_buy_id')
                ->join('tb_deal_state as tds', 'o.state_id', '=', 'tds.deal_state_id')
                ->where('o.declare_id', $request->invoice_id)
                ->where('tds.code_v', 'OFFER_NEW')
                ->where('tdo.user_id', $user->id)
                ->get();

            return response()->json(array(
                'status' => true,
                'offers' => $offers->toArray()
            ));

        }
        catch(Exception $ex) {
            \Log::error('get offer error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function getOfferDetail(Request $request) {
        $rules = array(
            'offer_id' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'offer_id.required' => 'Неверный запрос',
            'offer_id.integer' => 'Неверный запрос'
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $user = Auth::user();

        try {
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            $offer = DB::table('tb_offer as a')
                ->select('a.offer_id', 'a.declare_id', 'a.details_id', 'b.name_v as state_name')
                ->join('tb_deal_state as b', 'a.state_id', '=', 'b.deal_state_id')
                ->where('a.offer_id', $request->offer_id)
                ->first();

            if (is_null($offer)) {
                throw new Exception('Предложение не найдено');
            }

            $invoice = $this->_getInvoiceById($offer->details_id, $user->id);

            $status = true;

            if (is_null($invoice)) {
                $status = false;
            }

            return response()->json(array(
                'status' => $status,
                'invoice' => $invoice,
                'offer' => $offer
            ));

        }
        catch (Exception $ex) {
            \Log::error('get offer error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function agreeOffer(Request $request) {
        $rules = array(
            'offer_id' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'offer_id.required' => 'Неверный запрос',
            'offer_id.integer' => 'Неверный запрос'
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $user = Auth::user();

        try {
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

//            $offer = DB::table('tb_offer')
//                ->select('offer_id', 'declare_id', 'details_id')
//                ->where('offer_id', $request->offer_id)
//
//                ->first();

            $offer = Offer::where('offer_id', $request->offer_id)
                        ->with('detail')
                        ->with('origin')
                        ->first();

            if (is_null($offer)) {
                throw new Exception('Предложение не найдено');
            }

            $params = array(
                'Deal' => $offer->declare_id,
                'SellSum' => $offer->origin->sum_sell_nd,
                'SellCur' => $offer->origin->cur_sell_id,
                'SellAccDt' => $offer->origin->acc_dt_id,
                'SellAccCt' => $offer->detail->acc_ct_id,
                'BuySum' => $offer->origin->sum_buy_nd,
                'BuyCur' => $offer->origin->cur_buy_id,
                'BuyAccDt' => $offer->detail->acc_dt_id,
                'BuyAccCt' => $offer->origin->acc_ct_id
            );

            $result = \Api::execute('createDeal', $params);

            if ($result['status'] == false) {

                throw new \Exception('Ошибка АБС: '.$result['message']);
            }

            DB::select('call exec_offer_in_bank(?)', array($request->offer_id));
            DB::select('call exec_declare_in_bank(?)', array($offer->declare_id));

            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            \Log::error('Agree offer error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function disagreeOffer(Request $request) {
        $rules = array(
            'offer_id' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'offer_id.required' => 'Неверный запрос',
            'offer_id.integer' => 'Неверный запрос'
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $user = Auth::user();

        try {
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            DB::select('call exec_offer_refuse(?)', array($request->offer_id));

            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            \Log::error('Disagree offer error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function closeOffer(Request $request) {
        $rules = array(
            'offer_id' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'offer_id.required' => 'Неверный запрос',
            'offer_id.integer' => 'Неверный запрос'
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $user = Auth::user();

        try {
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            DB::select('call exec_offer_closed(?)', array($request->offer_id));

            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            \Log::error('Close offer error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function closeDeclare(Request $request) {
        $rules = array(
            'id' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'id.required' => 'Неверный запрос',
            'id.integer' => 'Неверный запрос'
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $user = Auth::user();

        try {
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            DB::select('call exec_declare_close(?)', array($request->id));

            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            \Log::error('Close declare error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function getInBankView() {
        return view('invoices.invoices-inbank');
    }

    public function getInvoiceState(Request $request) {
        $rules = array(
            'invoice_id' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'invoice_id.required' => 'Неверный запрос',
            'invoice_id.integer' => 'Неверный запрос',
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

            $params = array(
                'Deal' => $request->invoice_id
            );


            $result = \Api::execute('getDealState', $params);

            if ($result['status'] == false) {
                throw new \Exception('Ошибка АБС: '.$result['message']);
            }

            $status = false;
            $message = '';

            $msg = trim(strtolower($result['response']['msg']));

            $offer = Offer::where('declare_id', $request->invoice_id)
                ->with('detail', 'origin')
                ->whereHas('state', function($q){
                    $q->where('code_v', 'IN_BANK');
                })
                ->first();

            if (is_null($offer)) {
                throw new Exception('Произошла ошибка. Предложения не найдены');
            }

            if ($msg == null or ($msg != 'ok' and $msg != 'work')) {

                DB::select('call exec_offer_refuse_bank(?)', array($offer->details_id));
                DB::select('call exec_declare_refuse_bank(?)', array($offer->declare_id));

                $message = 'Отклонено банком';

            }
            else if ($msg == 'ok') {
                $status = true;
                DB::select('call exec_offer_close(?)', array($offer->offer_id));
                DB::select('call exec_declare_close(?)', array($offer->declare_id));
            }
            else {
                $status = true;
            }

            return response()->json(array(
                'status' => $status,
                'msg' => $msg,
                'message' => $message
            ));
        }
        catch(Exception $ex) {
            \Log::error('get state error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function getPaymentsView() {
        return view('invoices.invoices-detail-inbank');
    }

    public function getPayments(Request $request) {
        $rules = array(
            'invoice_id' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'invoice_id.required' => 'Неверный запрос',
            'invoice_id.integer' => 'Неверный запрос'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        try {
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new \Exception($errMsg);
            }

            $params = array(
                'Deal' => $request->invoice_id
            );

            $result = \Api::execute('getPayments', $params);

            if ($result['status'] == false) {
                throw new \Exception('Ошибка АБС: '.$result['message']);
            }

            $payments = array();

            if (isset($result['response']['Payments']) and !is_null($result['response']['Payments']) and !empty($result['response']['Payments'])) {
                $payments = $result['response']['Payments'];
            }

            return response()->json(array(
                'status' => true,
                'payments' => $payments
            ));
        }
        catch(\Exception $ex) {
            \Log::error('Get Payment error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

}
