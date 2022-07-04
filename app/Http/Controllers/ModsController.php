<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class ModsController extends Controller
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

    public function mods($page = 1)
    {
        if(!is_null($page)) {
            request()->merge([
                'page' => $page,
            ]);
        }
        $new = false;
        $games = Game::orderByLikes(
            Game::getGamesDefaultScopeQuery($this->current_locale->id, 20)
        )->paginate();
        $games->setPath(route('mods'));
        return view(
            'mods.index',
            compact(
            'games',
                'new'
            )
        );
    }

    public function newmods($page = 1)
    {
        if(!is_null($page)) {
            request()->merge([
                'page' => $page,
            ]);
        }
        $new = true;
        $games = Game::orderByNew(
            Game::getGamesDefaultScopeQuery($this->current_locale->id, 20)
        )->paginate();
        $games->setPath(route('new-mods'));

        return view(
            'mods.index',
            compact(
            'games',
                'new'
            )
        );
    }
}