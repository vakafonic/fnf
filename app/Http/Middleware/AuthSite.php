<?php
/**
 * Created by Artdevue.
 * User: artdevue - AuthSite.php
 * Date: 2020-02-06
 * Time: 18:14
 * Project: gamesgo.club
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class AuthSite
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
            return redirect('/');
        }

        if (Auth::guard($guard)->check() && (int)Auth::user()->ban == 1) {

            $this->guard()->logout();

            $request->session()->invalidate();

            return redirect('/')->withErrors(['message'=>'У вас нет прав для входа']);
        }

        return $next($request);
    }
}