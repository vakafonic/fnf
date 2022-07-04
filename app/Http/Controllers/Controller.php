<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Language;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Redirect;
use Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use AuthenticatesUsers;

    /**
     * @var int
     */
    public $per_page;

    /**
     * @var array
     */
    public $breadcrumb = [];

    /**
     * @var object
     */
    protected $current_locale;

    /**
     * @var array
     */
    protected $lang;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var bool
     */
    protected $mob;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mob = isMobileDevice();

        $this->per_page = config('site.page.per_page');
        $this->current_locale = Language::whereCode(LaravelLocalization::getCurrentLocale())->first();

        $this->lang = _lang($this->current_locale->id);
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if(Language::whereCode(LaravelLocalization::getCurrentLocale())->value('status') == 0 && (Auth::guest() || Auth::user()->role == 0)) {
                header("HTTP/1.1 410 Gone");
                header("Location: /error/");
                exit;
            }

            return $next($request);
        });

        view()->share('lang', $this->lang);
        view()->share('current_locale', $this->current_locale);
    }

    /**
     * View 403 ERROR page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function get403Code()
    {
        $message = 'У вас нет доступа к данному действию';
        if (Request::ajax()) {
            return response()->json(['error' => $message], 403);
        }

        return view('errors.403', ['message' => $message]);
    }

    /**
     * View 404 ERRPR page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function get404Code()
    {

        $message = 'Страница не найденна';
        if (Request::ajax()) {
            return response()->json(['error' => $message], 404);
        }

        $best_ids = Game::getGamesIdsOfGenryBest();
        $games = Game::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->where('games_langs.lang_id', $this->current_locale->id)
            ->where('games.public', 1)
            ->whereIn('games.id', $best_ids)
            ->orderBy('games.created_at', 'ASC')
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game',
                'games.rating', 'games.created_at', 'games_langs.name')
            ->paginate($this->per_page);

        $status = '404';

        return response()->view('errors.404', compact('message', 'games', 'status'))->setStatusCode(404);
    }
}
