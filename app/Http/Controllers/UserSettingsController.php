<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
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
}
