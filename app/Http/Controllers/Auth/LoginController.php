<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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

            if ($request->get('exp_time') >= time() && $user->id == $request->session()->get('user_id', -1)) {
                return response()->redirectTo('/login-step-2')->with('email', $user->email);
            }

            $pin = \Sms::generatePin();
            $exp_time = time() + (int)(env('SMS_TIMEOUT', '2') * 60);

            $request->session()->put('user_id', $user->id);
            $request->session()->put('exp_time', $exp_time);
            $request->session()->put('code', $pin);

            //$request->
            return response()->redirectTo('/login-step-2')->with('email', $user->email);

        }
        catch (Exception $exception) {
            return response()->redirectTo('/login')->with('custom_error', $exception->getMessage())->withInput();
        }

    }

    public function showSecondStep(Request $request) {
        return 'step 2 '.$request->session()->get('code', '');
    }

}
