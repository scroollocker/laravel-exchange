<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $user = Auth::user();

        $isAuth = !is_null($user);

        if (!$isAuth) {
            if (Request::wantsJson()) {
                return response()->json(array(
                    'status' => false,
                    'message' => 'Вы не авторизованы'
                ), 401);
            }
            $request->session()->flush();

            return response()->redirectTo('/login')->with('custom_error', 'Проидите авторизацию');
        }

        return $next($request);
    }
}
