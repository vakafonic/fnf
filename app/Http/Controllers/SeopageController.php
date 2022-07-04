<?php
/**
 * Created by Artdevue.
 * User: artdevue - SeopageController.php
 * Date: 2020-02-23
 * Time: 22:06
 * Project: gamesgo.club
 */

namespace App\Http\Controllers;


use App\Models\Game;
use App\Models\GamesGenre;
use App\Models\GamesHeroe;
use App\Models\Genre;
use App\Models\Seopage;
use App\Models\SeopagesGenre;
use App\Models\SeopagesHeroe;
use Illuminate\Http\Request;

class SeopageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        parent::__construct();
    }

    /**
     * @param         $slug
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getGames( string $slug, Request $request)
    {
        // Get Seopage
        $seopage = Seopage::whereUrl($slug)->wherePublic(1)->first();

        if(!$seopage) {
            return $this->get404Code();
        }

        $current_page = $request->get('page', 1);

        $seopage_lang = $seopage->langsArray($this->current_locale->id);

        return view('seopage.index', compact('seopage', 'seopage_lang', 'current_page'));
    }

    public function postGames(int $full, int $id, Request $request)
    {
        $current_page = $request->get('page', 1);
        $success = true;

        // Get Seopage
        $seopage = Seopage::whereId($id)->wherePublic(1)->first();
        $html = '';
        $show_more = false;

        if(!$seopage) {
            $success = false;
            return response()->json(compact('success', 'html', 'current_page', 'show_more'), 200);
        }

        $query = Game::query();

        $game_ids = [];

        // Get games Ids og Genry
        $seopage_genrys = SeopagesGenre::whereSeopagesId($id)->pluck('genry_id');
        $genries_ids = [];

        foreach ($seopage_genrys as $seopage_genry) {
            $genries_ids = array_merge_recursive(Genre::getParentGenreId($seopage_genry, 0), $genries_ids);
        }


        //array_unique($genries_ids);

        if (count($genries_ids) > 0) {
            $game_ids = GamesGenre::whereIn('genry_id', $genries_ids)->pluck('game_id')->toArray();
        }

        // Get games Ids og Heroes
        $heroes_ids = SeopagesHeroe::whereSeopagesId($id)->pluck('heroes_id')->toArray();
        if (count($heroes_ids) > 0) {
            $game_ids = array_intersect($game_ids, GamesHeroe::whereIn('heroy_id', $heroes_ids)->pluck('game_id')->toArray());
        }

        if(count($game_ids) > 0) {
            array_unique($game_ids);
        } else {
            $game_ids = ['9999999999'];
        }

        $query->rightJoin('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->where('games_langs.lang_id', $this->current_locale->id)
            ->where('games.public', 1)
            ->whereIn('games.id', $game_ids);

        $query->orderBy('games.created_at', 'ASC')
            ->groupBy('games.id')
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game',
                'games.rating', 'games.created_at', 'games_langs.name');

        $games = $query->paginate($this->per_page);
        //$games->withPath($seopage->url);
        $games->setPath(route('seo.url', ['slug' => $seopage->url]));

        $html = view('games.url_post', compact('games', 'full'))->render();

        $show_more = $games->currentPage() != $games->lastPage();

        return response()->json(compact('success', 'html', 'current_page', 'show_more'), 200);
    }
}