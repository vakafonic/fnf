<?php
/**
 * Created by Artdevue.
 * User: artdevue - trailingSlashes.php
 * Date: 2020-03-25
 * Time: 19:08
 * Project: gamesgo.club
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Config;

class trailingSlashes
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
        if (substr($request->getRequestUri(), 0, 4) == '/en/') {
            return Redirect::to(substr($request->getRequestUri(), 3), 301);
        }

        if ($request->getRequestUri() == '/en') {
            return Redirect::to('/', 301);
        }

        $reguest_url = explode('?', $request->getRequestUri());

        if (!preg_match('/.+\/$/', $reguest_url[0]))
        {
            if($reguest_url[0] != '/') {
                $base_url = trim(Config::get('app.url'), '/');
                $reguest_url[0] = $base_url.$reguest_url[0].'/';
                return Redirect::to(implode('?', $reguest_url), 301);
            }
        }
        return $next($request);
    }
}