<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use AuthenticatesUsers;

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var User $user
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user= Auth::user();

            return $next($request);
        });
    }

    /**
     * Get content title.
     *
     * @return string
     */
    /*protected function title()
    {
        return $this->title;
    }

    public function destroy()
    {
        View::share('title', $this->title);
        View::share('description', $this->description);
        die(var_dump('3424'));
    }*/

    public function get403Code()
    {
        $message = 'У вас нет доступа к данному действию';
        if(request()->ajax())
        {
            return response()->json(['error' => $message], 403);
        }

        return view('manager.errors.403', ['message' => $message]);
    }
}
