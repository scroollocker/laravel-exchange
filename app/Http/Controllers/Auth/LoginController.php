<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    private function sendPinCode($request, $user) {
        $pin = \Sms::generatePin();
        $exp_time = time() + (int)(env('SMS_TIMEOUT', '2') * 60);

        $request->session()->put('exp_time', $exp_time);
        $request->session()->put('code', '12345');
        //$request->session()->put('code', $pin);

        //\Sms::sendPin($pin, $user);
    }

    public function firstStepAuth(Request $request) {

        $messages = [
            'email.required' => 'Поле E-mail обязательно',
            'password.required' => 'Поле E-mail обязательно',
            'email.email' => 'Поле E-mail должно содержать корректный E-mail',
            'password.min' => 'Пароль слишком короткий'
        ];

        $rules = [
            'email' => [
                'required',
                'email'
            ],
            'password' => [
                'required',
                'min:3'
            ]
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->redirectTo('/login')->withErrors($validator)->withInput();
        }

        try {
            $user = User::where('email', $request->email)
                ->get()
                ->first();

            if (is_null($user)) {
                throw new Exception('Невернй email или пароль');
            }
            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('Невернй email или пароль');
            }
            if ($user->blocked == 1) {
                throw new Exception('Пользователь заблокирован.');
            }

            if ($request->get('exp_time') >= time() && $user->id == $request->session()->get('user_id', -1)) {
                return response()->redirectTo('/login-step-2');
            }

            $request->session()->put('user_id', $user->id);

            return response()->redirectTo('/login-step-2');

        }
        catch (Exception $exception) {
            return response()->redirectTo('/login')->with('custom_error', $exception->getMessage())->withInput();
        }

    }

    public function showSecondStep(Request $request) {
        $user = User::find($request->session()->get('user_id', '-1'));

        if (is_null($user)) {
            $request->session()->flush();
            return response()->redirectTo('/login')->with('custom_error', 'Пользователь не найден');

        }

        if ($request->session()->get('exp_time', '0') <= time()) {
            $this->sendPinCode($request, $user);
        }

        $data = [
            'user' => $user,
            'exp_time' => $request->session()->get('exp_time', '0')
        ];

        return view('auth.login-confirm', $data);
    }

    public function confirmPin(Request $request) {
        $messages = [
            'sms.required' => 'Не заполнено поле SMS. Новый код выслан.',
            'sms.min' => 'Неверный Пин. Новый код выслан.',
            'sms.max' => 'Неверный Пин. Новый код выслан.'
        ];

        $rule = [
            'sms' => [
                'required',
                'min:'.env('PIN_CODE_LEN', '5'),
                'max:'.env('PIN_CODE_LEN', '5')
            ]
        ];



        $user = User::find($request->session()->get('user_id', '-1'));

        if (is_null($user)) {
            $request->session()->flush();
            return response()->redirectTo('/login')->with('custom_error', 'Пользователь не найден');
        }

        $validator = Validator::make($request->all(), $rule, $messages);

        if ($validator->fails()) {
            $this->sendPinCode($request, $user);
            \Log::error([
                'action' => 'confirm-pin',
                'type' => 'validation error',
                'message' => $validator->errors->all()
            ]);
            return response()->redirectTo('/login-step-2')->withInput()->withErrors($validator);
        }

        try {
            $user_pin = $request->sms;
            $session_pin = $request->session()->get('code', '');

            if ($user_pin != $session_pin) {
                throw new Exception('Неверный пин . Новый пин отправлен');
            }

            if ($request->session()->get('exp_time', '0') <= time()) {
                throw new Exception('Неверный пин. Новый пин отправлен.');
            }

            $request->session()->flush();
            Auth::loginUsingId($user->id);
            $request->session()->put('user', $user->toArray());
            return response()->redirectTo('/home');

        }
        catch (Exception $exception) {
            \Log::error([
                'action' => 'cofirm-pin',
                'type' => 'exception',
                'message' => $exception->getMessage()
            ]);

            $this->sendPinCode($request, $user);
            return response()->redirectTo('/login-step-2')->with('custom_error', $exception->getMessage());
        }


    }

}
