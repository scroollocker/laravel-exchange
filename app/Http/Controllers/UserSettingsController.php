<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showUserSettingsView() {
        return view('settings.user-settings');
    }

    public function getUserSettings() {
        try {
            $user = \Auth::user();

            if (is_null($user)) {
                throw new Exception('Пользователь не найден');
            }
            $user = User::select(array('email', 'autoconfirm', 'phone'))->where('id',$user->id)->first();
            if (is_null($user)) {
                throw new Exception('Пользователь не найден');
            }

            return response()->json(array(
                'status' => true,
                'settings' => $user->toArray()
            ));

        } catch(Exception $ex) {
            \Log::error($ex);
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function saveUserSettings(Request $request) {
        $messages = array(
            'autoconfirm.required' => 'Поле Автоподтверждение обязательно',
            'autoconfirm.integer' => 'Поле Автоподтверждение имеет неверный формат',
            'phone.required' => 'Поле Телефон обязательно',
            'phone.max' => 'Поле Телефон имеет неверную длину',
            'email.email' => 'Поле E-mail имеет неверный формат'
        );

        $rules = array(
            'autoconfirm' => array(
                'required', 'integer'
            ),
            'phone' => array(
                'required', 'max:12'
            ),
            'email' => array(
                'email'
            )
        );
        try {

            $changePassword = $request->changePassword;

            if (is_null($changePassword) or !isset($changePassword)) {
                $changePassword = 'false';
            }
            if ($changePassword and $changePassword == 'true') {

                $messages['old_password.required'] = 'Текущий пароль обязателен';
                $messages['new_password.required'] = 'Новый пароль обязателен';
                $messages['new_password.min'] = 'Минимальная длина нового пароля 6 символов';
                $messages['new_password.confirmed'] = 'Пароли не равны';

                $rules['old_password'] = array('required');
                $rules['new_password'] = array('required', 'min:6', 'confirmed');
            }

            //var_dump($rules);return;

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            $user = \Auth::user();

            $user = User::find($user->id);

            if (is_null($user)) {
                throw new Exception('Пользователь не найден');
            }

            if ($changePassword == 'true') {
                if ($user->confirmPassword($request->old_password)) {
                    $user->password = bcrypt($request->new_password);
                }
                else {
                    throw new Exception('Пароль введен неверный');
                }
            }

            $user->phone = $request->phone;
            if ($request->email != $user->email) {
                $tmp = User::where('email', $request->email)->get()->first();
                if (is_null($tmp)) {
                    $user->email = $request->email;
                }
                else {
                    throw new Exception('E-mail уже существует');
                }
            }



            $user->autoconfirm = $request->autoconfirm;

            $user->save();

            \Log::info('User settings save');

            return response()->json(array(
               'status' => true
            ));

        } catch (Exception $ex) {
            \Log::error($ex);
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function accountsView() {

        return view('settings.user-accounts');
    }

    public function getUserAccounts() {
        try {
            $user = \Auth::user();

            if (is_null($user)) {
                throw new Exception('Вы не авторизованы');
            }

            $params = array(
                'Customer' => $user->ibs_id
            );

            $result = \Api::execute('getAccounts', $params);

            if ($result['status'] == false) {
                throw new \Exception('Ошибка АБС: '.$result['message']);
            }

            $acc = isset($result['response']['Accounts']) ? $result['response']['Accounts'] : array();

            foreach($acc as $item) {
                DB::select('call update_acc(?,?,?,?,?,?,?,?);', array(
                    $user->id,
                    $item['num_v'],
                    $item['cur_id'],
                    $item['saldo_nd'],
                    $item['limit_nd'],
                    $item['name_v'],
                    $item['state_id'],
                    $item['is_loan_n']
                ));
            }

            $accounts = \AccountDb::getAccounts($user->id);

            return response()->json(array(
                'status' => true,
                'accounts' => $accounts
            ));

        }
        catch(Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function enableUserAccount(Request $request) {
        $messages = array(
            'account_id.required' => 'Неверный запрос',
            'account_id.integer' => 'Параметр имеет неверный формат',
            'account_state.required' => 'Неверный запрос',
            'account_state.integer' => 'Параметр имеет неверный формат'
        );

        $rules = array(
            'account_id' => array(
                'required', 'integer'
            ),
            'account_state' => array(
                'required', 'integer'
            )
        );
        try {
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            /* TODO: check for user account */

            \AccountDb::setAccountState($request->account_id, $request->account_state);

            return response()->json(array(
                'status' => true
            ));

        }
        catch(Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function partnersView() {
        return view('settings/user-partners');
    }

    public function getPartners(Request $request) {
        try {
            $user = \Auth::user();

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            $partners = \DB::table('tb_user_list as ul')
                            ->join('users as u', 'u.id', '=', 'ul.user_id')
                            ->join('users as p', 'p.id', '=', 'ul.partner_id')
                        ->select('ul.user_list_id AS id', 'ul.user_id AS user_id', 'ul.partner_id AS partner_id', 'p.email AS partner_email', 'ul.state_n as state')
                        ->get();

            return response()->json(array(
                'status' => true,
                'partners' => $partners->toArray()
            ));

        } catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function setPartnersState(Request $request) {

        $messages = array(
            'partner_id.required' => 'Неверный запрос',
            'partner_id.integer' => 'Неверный формат запроса',
            'state.required' => 'Неверный запрос',
            'state.regex' => 'Невернй формат запроса'
        );

        $rules = array(
            'partner_id' => array(
                'required', 'integer'
            ),
            'state' => array(
                'required', 'regex:/^[1|2]$/'
            )
        );

        try {

            $user = Auth::user();

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            \DB::select('call merge_user_list(?,?,?);', array($user->id, $request->partner_id, $request->state));

            /*\DB::table('tb_user_list')
                ->where('user_id', $user->id)
                ->where('partner_id', $request->partner_id)
                ->update(array(
                    'state_n' => $request->state
                ));
            */
            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }

    public function removePartner(Request $request) {
        $messages = array(
            'partner_id.required' => 'Неверный запрос',
            'partner_id.integer' => 'Неверный формат запроса'
        );

        $rules = array(
            'partner_id' => array(
                'required', 'integer'
            )
        );

        try {
            $validator = Validator::make($request->all(), $rules, $messages);

            $user = Auth::user();

            if (is_null($user)) {
                throw new Exception('Пользователь не авторизован');
            }

            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            \DB::select('call delete_user_list(?,?);', array($user->id, $request->partner_id));

            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function getUserList(Request $request) {
        $messages = array(
            'page.integer' => 'Неверный запрос'
        );

        $rules = array(
            'page' => array(
                'integer', 'nullable'
            ),
            'q' => array(
                'nullable'
            )
        );
        try {
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errMsg = '';
                foreach ($validator->errors()->all() as $error) {
                    $errMsg .= $error;
                }
                throw new Exception($errMsg);
            }

            $q = ($request->q) ? $request->q : '';

            $users = \DB::table('users')
                        ->select('id', 'email','name')
                        ->where('blocked', '0')
                        ->where('email', 'like', '%'.$q.'%')
                        ->paginate(15);

            return response()->json(array(
                'status' => true,
                'paginator' => $users->toArray()
            ));

        } catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }

    public function savePartner(Request $request) {
        $messages = array(
            'partner_id.required' => 'Неверный запрос',
            'partner_id.integer' => 'Невернй формат',
            'state.required' => 'Неверный запрос',
            'state.integer' => 'Невернй формат'
        );

        $rules = array(
            'partner_id' => array(
                'required', 'integer'
            ),
            'state' => array(
                'required', 'integer'
            )
        );
        try {
            $validator = Validator::make($request->all(), $rules, $messages);
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

            \DB::select('call merge_user_list(?, ?, ?);', array($user->id, $request->partner_id, $request->state));

            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }
}
