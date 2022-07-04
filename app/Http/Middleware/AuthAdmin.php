<?php
/**
 * Created by Artdevue.
 * User: artdevue - AuthAdmin.php
 * Date: 2019-12-01
 * Time: 15:29
 * Project: gamesgo.club
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{

    use AuthenticatesUsers;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            return redirect('/login');
        }

        if (Auth::guard($guard)->check() && (int)Auth::user()->role < 1) {

            $this->guard()->logout();

            $request->session()->invalidate();

            return redirect('/login')->withErrors(['message1'=>'У вас нет прав для входа в длмин панель']);
        }

        return $next($request);
    }
}