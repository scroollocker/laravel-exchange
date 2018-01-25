<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsBlock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {

            $user = Auth::user();

            if (is_null($user)) {
                throw new \Exception('Пользователь не авторизован');
            }

            if ($user->blocked) {
                throw new \Exception('Ваша учетная запись заблокирована');
            }

            return $next($request);
        }
        catch (\Exception $exception) {
            \Log::error([
                'action' => 'middleware-admin-exception',
                'message' => $exception->getMessage()
            ]);

            $request->session()->flush();

            return response()->redirectTo('/login')->with('custom_error', $exception->getMessage());
        }

    }
}
