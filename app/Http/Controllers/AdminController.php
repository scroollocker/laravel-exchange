<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Setting;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function userList() {
        return view('admin.user-list');
    }

    public function currencyList() {
        return view('admin.currency-list');
    }

    public function getCurrencyList() {
        $currencies = Currency::all();

        return response()->json(array(
            'status' => true,
            'currencies' => $currencies->toArray()
        ));
    }

    public function addNewCurrency(Request $request) {

        $messages = array(
            'cur_code.required' => 'Поле код валюты обязателен к заполнению',
            'cur_code.max' => 'Поле код валюты превышает допустимый размер символов',
            'cur_name.max' => 'Поле наименование валюты превышает допустимый размер символов',
            'cur_name.required' => 'Поле наименование валюты превышает допустимый размер символов'
        );

        $rules = array(
            'cur_code' => array(
                'required', 'max:20'
            ),
            'cur_name' => array(
                'required', 'max:50'
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

            $currency = new Currency;

            $currency->cur_code = $request->cur_code;
            $currency->cur_name = $request->cur_name;
            $currency->cur_enable = 1;

            $currency->save();

            \Log::info('Save currency');
            \Log::info($currency->toArray());

            return response()->json(array(
                'status' => true
            ));

        }
        catch (Exception $ex) {
            \Log::error($ex->getMessage());
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }

    public function editCurrency(Request $request) {

        $messages = array(
            'id.required' => 'Неверный запрос',
            'id.integer' => 'Неверный запрос',
            'cur_code.required' => 'Поле код валюты обязателен к заполнению',
            'cur_code.max' => 'Поле код валюты превышает допустимый размер символов',
            'cur_name.max' => 'Поле наименование валюты превышает допустимый размер символов',
            'cur_name.required' => 'Поле наименование валюты превышает допустимый размер символов'
        );

        $rules = array(
            'id' => array(
                'required', 'integer'
            ),
            'cur_code' => array(
                'required', 'max:20'
            ),
            'cur_name' => array(
                'required', 'max:50'
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

            $currency = Currency::find($request->id);

            if (is_null($currency)) {
                throw new Exception('Валюта не найдена');
            }

            $currency->cur_code = $request->cur_code;
            $currency->cur_name = $request->cur_name;

            $currency->save();

            \Log::info('Edit currency');
            \Log::info($currency->toArray());

            return response()->json(array(
                'status' => true
            ));

        }
        catch (Exception $ex) {
            \Log::error($ex->getMessage());
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

    }

    public function blockCurrency(Request $request) {

        $messages = array(
            'id.required' => 'Неверный запрос',
            'id.integer' => 'Неверный запрос',
            'block.integer' => 'Неверный запрос',
            'block.required' => 'Неверный запрос'
        );

        $rules = array(
            'id' => array(
                'required', 'integer'
            ),
            'block' => array(
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

            $currency = Currency::find($request->id);

            if (is_null($currency)) {
                throw new Exception('Валюта не найдена');
            }

            \Log::info('lock\unlock currency');
            \Log::info($currency->toArray());

            $currency->cur_enable = $request->block;

            $currency->save();

            return response()->json(array(
                'status' => true
            ));

        }
        catch (Exception $ex) {
            \Log::error($ex->getMessage());
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function settingsList() {
        return view('admin.settings-list');
    }

    public function getSeeting(Request $request) {
        $setting = Setting::all()->first();

        return response()->json(array(
            'status' => true,
            'setting' => $setting
        ));
    }

    public function saveSetting(Request $request) {
        $messages = array(
            'settings_err_count.required' => 'поле Количество ошибок обязательно',
            'settings_err_count.integer' => 'поле Количество ошибок имеет неверный формат',
            'settings_day.required' => 'поле Дней на операцию обязательно',
            'settings_day.integer' => 'поле Дней на операцию имеет неверный формат',
            'settings_op_count.required' => 'поле Дней на операцию обязательно',
            'settings_op_count.integer' => 'поле Дней на операцию имеет неверный формат'
        );

        $rules = array(
//            'id' => array(
//                'required', 'integer'
//            ),
            'settings_err_count' => array(
                'required', 'integer'
            ),
            'settings_day' => array(
                'required', 'integer'
            ),
            'settings_op_count' => array(
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
                throw new Exceptisettings_err_counton($errMsg);
            }

            $settingId = $request->id;
            $settingModel = null;
            if ($settingId) {
                $settingModel = Setting::find($settingId);
            }

            if (is_null($settingModel)) {
                $settingModel = new Setting;
            }

            $settingModel->settings_err_count = $request->settings_err_count;
            $settingModel->settings_day = $request->settings_day;
            $settingModel->settings_op_count = $request->settings_op_count;

            $settingModel->save();

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

    public function getUserList() {
        $users = User::where('isAdmin', '<>', '1')->get();

        return response()->json(array(
            'status' => true,
            'users' => $users->toArray()
        ));
    }

    public function blockUser(Request $request) {
        $userId = $request->user_id;
        $blockState = $request->block_state;
        try {
            $user = User::where('id', $userId)
                          ->where('isAdmin', '0')
                          ->first();
            if (is_null($user)) {
                throw new Exception('Пользователь не найден');
            }
            $user->blocked = $blockState;
            $user->save();
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

    public function addUser(Request $request) {
        $messages = array(
            'name.required' => 'Поле Имя обязательно для заполнения',
            'name.max' => 'Поле Имя обязательно для заполнения',
            'email.email' => 'Поле E-Mail имеет неверный формат',
            'email.required' => 'Поле E-Mail должно быть обязательно',
            'email.max' => 'Поле E-Mail имеет большую длину',
            'phone.required' => 'Поле Телефон должно быть обязательно',
            'phone.max' => 'Поле Телефон имеет неверную длину',
            'ibs_id.required' => 'Поле АБС ID обязательно',
            'ibs_id.integer' => 'Поле АБС ID имеет неверный формат',
            'invoice_count.required' => 'Поле Количество сделок обязательно',
            'invoice_count.integer' => 'Поле Количество сделок имеет неверный формат',
            'active_date.date_format' => 'Поле Дата имеет неверный формат',
            'comment.max' => 'Поле Комментарий имеет неверную длину',
            'email.unique' => 'Пользователь с таким E-mail существует'
        );

        $rules = array(
            'name' => array(
                'required', 'max:100'
            ),
            'email' => array(
                'email', 'required','max:150', 'unique:users,email'
            ),
            'phone' => array(
                'required', 'max:60'
            ),
            'ibs_id' => array(
                'required', 'integer'
            ),
            'invoice_count' => array(
                'required', 'integer'
            ),
            'active_date' => array(
                'date_format:Y-m-d H:i:s'
            ),
            'comment' => array(
                'max:500'
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

            $userPin = \Sms::generatePin();

            $newUser = new User;

            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->phone = $request->phone;
            $newUser->ibs_id = $request->ibs_id;
            $newUser->invoice_count = $request->invoice_count;
            $newUser->active_date = $request->active_date;
            $newUser->comment = $request->comment;
            $newUser->password = bcrypt($userPin);
            $newUser->blocked = 0;
            $newUser->recreatePwd = 1;

            $newUser->save();

            \Log::info('Save user');
            \Log::info($newUser->toArray());
            \Log::info($userPin);

            $smsStr = 'Пароль для входа - '.$userPin;
            \Sms::sendSms($newUser->phone, $smsStr);

            return response()->json(array(
                'status' => true
            ));

        } catch (Exception $ex) {
            \Log::error($ex->getMessage());
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function editUser(Request $request) {
        $messages = array(
            'id.required' => 'Неверный запрос',
            'name.required' => 'Поле Имя обязательно для заполнения',
            'name.max' => 'Поле Имя обязательно для заполнения',
            'email.email' => 'Поле E-Mail имеет неверный формат',
            'email.required' => 'Поле E-Mail должно быть обязательно',
            'email.max' => 'Поле E-Mail имеет большую длину',
            'phone.required' => 'Поле Телефон должно быть обязательно',
            'phone.max' => 'Поле Телефон имеет неверную длину',
            'ibs_id.required' => 'Поле АБС ID обязательно',
            'ibs_id.integer' => 'Поле АБС ID имеет неверный формат',
            'invoice_count.required' => 'Поле Количество сделок обязательно',
            'invoice_count.integer' => 'Поле Количество сделок имеет неверный формат',
            'active_date.date_format' => 'Поле Дата имеет неверный формат',
            'comment.max' => 'Поле Комментарий имеет неверную длину',
            'email.unique' => 'Пользователь с таким E-mail существует'
        );

        $rules = array(
            'id' => array(
                'required'
            ),
            'name' => array(
                'required', 'max:100'
            ),
            'email' => array(
                'email', 'required','max:150',
            ),
            'phone' => array(
                'required', 'max:60'
            ),
            'ibs_id' => array(
                'required', 'integer'
            ),
            'invoice_count' => array(
                'required', 'integer'
            ),
            'active_date' => array(
                'date_format:Y-m-d H:i:s'
            ),
            'comment' => array(
                'max:500'
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

            //$userPin = \Sms::generatePin();

            $newUser = User::where('id', $request->id)->first();

            if (is_null($newUser)) {
                throw new Exception('Пользователь не найден');
            }

            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->phone = $request->phone;
            $newUser->ibs_id = $request->ibs_id;
            $newUser->invoice_count = $request->invoice_count;
            $newUser->active_date = $request->active_date;
            $newUser->comment = $request->comment;
            $newUser->save();

            \Log::info('Edit user');
            \Log::info($newUser->toArray());

            return response()->json(array(
                'status' => true
            ));

        } catch (Exception $ex) {
            \Log::error($ex->getMessage());
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function removeUser(Request $request) {
        $messages = array(
            'user_id.required' => 'Неверный запрос',
            'user_id.integer' => 'Неверный запрос'
        );

        $rules = array(
            'user_id' => array(
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

            $user = User::where('id', $request->user_id)
                            ->where('isAdmin', '0')
                            ->first();
            if (is_null($user)) {
                throw new Exception('Пользователь не найден');
            }

            $user->delete();
            \Log::info('User has been deleted');
            \Log::info($user->toArray());

            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            \Log::error($ex->getMessage());
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

    public function resetPassword(Request $request) {
        $messages = array(
            'user_id.required' => 'Неверный запрос',
            'user_id.integer' => 'Неверный запрос'
        );

        $rules = array(
            'user_id' => array(
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

            $userPin = \Sms::generatePin();

            $user = User::where('id', $request->user_id)
                ->first();
            if (is_null($user)) {
                throw new Exception('Пользователь не найден');
            }

            $user->password = bcrypt($userPin);
            $user->recreatePwd = 1;

            \Log::info('User password has been updated');
            \Log::info($user->toArray());
            \Log::info($userPin);

            return response()->json(array(
                'status' => true
            ));
        }
        catch (Exception $ex) {
            \Log::error($ex->getMessage());
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }



}
