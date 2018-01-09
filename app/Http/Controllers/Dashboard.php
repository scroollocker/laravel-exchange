<?php

namespace App\Http\Controllers;

use App\Account;
use App\Currency;
use App\Invoice;
use App\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Dashboard extends Controller
{
    public function templateInvoiceList() {
        return view('dashboard.invoices-list');
    }

    public function templateEditOffer() {
        return view('dashboard.offer-add');
    }

    public function getAvailibleInvoices(Request $request) {

        $rules = array(
            'buy_cur' => array(
                'nullable'
            ),
            'sell_cur' => array(
                'nullable'
            ),
            'buy_from' => array(
                'nullable', 'integer', 'min:0'
            ),
            'buy_to' => array(
                'nullable', 'integer'
            ),
            'sell_from' => array(
                'nullable', 'integer', 'min:0'
            ),
            'sell_to' => array(
                'nullable', 'integer'
            ),
            'course_from' => array(
                'nullable', 'integer', 'min:0'
            ),
            'course_to' => array(
                'nullable', 'integer'
            )
        );

        $messages = array(
            'buy_from.integer' => 'Неверный формат данных',
            'buy_from.min' => 'Начальный диапозон должен быть больше 0',
            'buy_to.integer' => 'Неверный формат данных',
            'sell_from.integer' => 'Неверный формат данных',
            'sell_from.min' => 'Начальный диапозон должен быть больше 0',
            'sell_to.integer' => 'Неверный формат данных',
            'course_from.integer' => 'Неверный формат данных',
            'course_from.min' => 'Начальный диапозон должен быть больше 0',
            'course_to.integer' => 'Неверный формат данных',

        );

        try {
            $user = \Auth::user();

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new \Exception($errMsg);
            }

            if (is_null($user)) {
                throw new \Exception('Пользователь не авторизован');
            }


            $builder = Invoice::whereHas('user', function($q) use ($user) {
                $q->where('id', '<>', $user->id);
            })->whereHas('type', function($q) {
                $q->where('code_v', 'DECLARE');
            })->whereHas('state', function($q) {
                $q->where('code_v', 'OPENED');
            })->with(array('user' => function($q) {
                $q->select('id', 'email');
            }))->with(array('type' => function($q) {
                $q->select('declare_type_id', 'name_v');
            }))->with(array('state' => function($q) {
                $q->select('deal_state_id', 'name_v');
            }));

            if (isset($request->buy_cur) && $request->buy_cur != null) {
                $builder->whereHas('currency_buy', function($q) use ($request) {
                    $q->where('id', $request->buy_cur['id']);
                });
            }

            if (isset($request->sell_cur) && $request->sell_cur != null) {
                $builder->whereHas('currency_sell', function($q) use ($request) {
                    $q->where('id', $request->sell_cur['id']);
                });
            }

            if (isset($request->buy_from) && $request->buy_from != null) {
                $builder->where('sum_buy_nd', '>=', $request->buy_from);
            }

            if (isset($request->buy_to) && $request->buy_to != null) {
                $builder->where('sum_buy_nd', '<=', $request->buy_to);
            }

            if (isset($request->sell_from) && $request->sell_from != null) {
                $builder->where('sum_sell_nd', '>=', $request->sell_from);
            }

            if (isset($request->sell_to) && $request->sell_to != null) {
                $builder->where('sum_sell_nd', '<=', $request->sell_to);
            }

            if (isset($request->course_from) && $request->course_from != null) {
                $builder->where('course_nd', '>=', $request->course_from);
            }

            if (isset($request->course_to) && $request->course_to != null) {
                $builder->where('course_nd', '<=', $request->course_to);
            }

            $invoices = $builder->get();

            $invoices->load('acc_dt', 'acc_ct', 'currency_sell', 'currency_buy');

            return response()->json(array(
                'status' => true,
                'invoices' => $invoices->toArray()
            ));
        }
        catch(\Exception $ex) {
            \Log::error('get invoice error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }

    public function getCurrencies() {
        try {
            $user = \Auth::user();

            if (is_null($user)) {
                throw new \Exception('Пользователь не авторизован');
            }

            $currencies = Currency::where('cur_enable', 1)->select('id', 'cur_name', 'cur_code')->get();

            return response()->json(array(
                'status' => true,
                'currencies' => $currencies->toArray()
            ));
        }
        catch(\Exception $ex) {
            \Log::error('get currences error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }


    public function getInvoice(Request $request) {
        $rules = array(
            'invoice_id' => array(
                'required', 'integer'
            ),
            'offer_id' => array(
                'nullable', 'integer'
            )
        );

        $messages = array(
            'invoice_id.required' => 'Неверный запрос',
            'invoice_id.integer' => 'Неверный запрос',
            'offer_id.integer' => 'Неверный запрос'
        );

        try {
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new \Exception($errMsg);
            }

            $invoice = Invoice::where('declare_id', $request->invoice_id)
                ->with('acc_dt', 'acc_ct', 'currency_sell', 'currency_buy')

                ->first();

            if (is_null($invoice)) {
                throw new \Exception('Заявка не найдена');
            }

            $offer = null;

            if (isset($request->offer_id) && !is_null($request->offer_id)) {
                $offer = Offer::where($request->offer_id)
                    ->with('origin', 'state', 'detail', 'detail.currency_sell', 'detail.currency_buy', 'detail.acc_dt', 'detail.acc_ct')
                    ->first();

            }

            return response()->json(array(
                'status' => true,
                'offer' => $offer,
                'invoice' => $invoice
            ));
        }
        catch (\Exception $ex) {
            \Log::error('get invoice error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function getAcc(Request $request) {
        $rules = array(
            'currency_buy' => array(
                'required', 'integer'
            ),
            'currency_sell' => array(
                'required', 'integer'
            )
        );

        $messages = array(
            'currency_buy.required' => 'Неверный запрос',
            'currency_buy.integer' => 'Неверный запрос',
            'currency_sell.required' => 'Неверный запрос',
            'currency_sell.integer' => 'Неверный запрос'
        );

        try {
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new \Exception($errMsg);
            }

            $acc_sell = Account::where('cur_id', $request->currency_sell)
                ->where('for_deal_n', 1)
                ->select('acc_id', 'name_v as acc_name', 'num_v as acc_num')
                ->get();

            $acc_buy = Account::where('cur_id', $request->currency_buy)
                ->where('for_deal_n', 1)
                ->select('acc_id', 'name_v as acc_name', 'num_v as acc_num')
                ->get();

            return response()->json(array(
                'status' => true,
                'acc_sell' => $acc_sell->toArray(),
                'acc_buy' => $acc_buy->toArray()
            ));

        }
        catch (\Exception $ex) {
            \Log::error('get acc error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }
}
