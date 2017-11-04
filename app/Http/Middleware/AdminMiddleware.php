<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        $user = \Auth::user();

        try {
            if (is_null($user)) {
                throw new \Exception('Вы не авторизованы');
            }

            if (!$user->isAdmin) {
                throw new \Exception('Вам запрещен доступ в данный раздел');
            }

            //dd($user->isAdmin);

            return $next($request);

        }
        catch(\Exception $exception) {
            \Log::error([
                'action' => 'middleware-admin-exception',
                'message' => $exception->getMessage()
            ]);

            return response()->redirectTo('/login')->with('custom_error', $exception->getMessage());
        }

    }
}
