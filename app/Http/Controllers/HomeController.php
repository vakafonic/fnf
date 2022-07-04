<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Page;
use App\Models\UserActivation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = Page::whereUrl('/')->first();
        $lang_page = $page->langsArray($this->current_locale->id);
        $mainpageGames = Game::orderByLikes(Game::getGamesDefaultScopeQuery($this->current_locale->id, 10))->get();

        return view('home', compact(
            'page',
            'lang_page',
            'mainpageGames'
        ));
    }


    public function postGames(Request $request)
    {
        $current_page = $request->get('page', 1);

        $games = Game::orderByLikes(Game::getGamesDefaultScopeQuery($this->current_locale->id, 10))
            ->paginate(10);

        $html = view('games.url_post', compact('games'))->render();
        $show_more = $games->currentPage() != $games->lastPage();
        $success = true;
        $output = compact('success', 'html', 'current_page', 'show_more');

        return response()->json($output);
    }
}
