<?php

namespace App\Http\Controllers;

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

    public function settingsList() {
        return view('admin.settings-list');
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
                'date_format:Y-m-d'
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

            \Log::info($userPin);

            return response()->json(array(
                'status' => true
            ));

        } catch (Exception $ex) {
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
                'date_format:Y-m-d'
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
            //$newUser->password = bcrypt($userPin);
            //$newUser->blocked = 0;
            //$newUser->recreatePwd = 1;

            $newUser->save();

            //\Log::info($userPin);

            return response()->json(array(
                'status' => true
            ));

        } catch (Exception $ex) {
            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

}
