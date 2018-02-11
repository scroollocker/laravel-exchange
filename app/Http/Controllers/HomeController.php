<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function admin()
    {
        return view('admin');
    }

    public function getCurrencyCourse() {
        try {
            $user = Auth::user();

            if (is_null($user)) {
                throw new \Exception('Пользователь не авторизован');
            }

            $currencies = Currency::select('cur_code', 'course_sell_nd', 'course_buy_nd')
                ->where('cur_enable', '1')
                ->get();

            return response()->json(array(
                'status' => true,
                'currencies' => $currencies->toArray()
            ));
        }
        catch(\Exception $ex) {
            \Log::error('Get currency course error');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }
    }

}
