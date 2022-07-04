<?php
/**
 * Created by Artdevue.
 * User: artdevue - SearchController.php
 * Date: 2020-02-11
 * Time: 23:49
 * Project: gamesgo.club
 */

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Heroes;
use App\Models\Page;
use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
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

    public function index(string $word)
    {
        $word = trim(strip_tags($word));

        if (!empty($word)) {
            // Search Genre
            $genres = Genre::searchWord($word, $this->current_locale->id);
            $heroes = Heroes::searchWord($word, $this->current_locale->id);
            $games = $this->_search_data($word);
            //$games->withPath('search/' . $word);
        }

        if (!empty($word) && ($genres->count() > 0 || $games->count() > 0 || $heroes->count() > 0)) {
            return view('search', compact('genres', 'word', 'games', 'heroes'));
        }
        $page = Page::whereUrl('/')->first();
        $lang_page = $page->langsArray($this->current_locale->id);

        $populars = Genre::getPopular($this->current_locale->id);
        $gamesController = new GameController();
        $bestGames = $gamesController->modelGames(true, 10);
        $games = $gamesController->modelGames();

        return view('search_no', compact('word','bestGames', 'page', 'lang_page', 'populars', 'games'));
    }

    /**
     * @param string $word
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function postIndex(string $word)
    {
        $word = trim(strip_tags($word));
        $type = request()->get('type', 'no_search');
        $current_page = request()->get('page', 1);
        $success = true;

        if (!empty($type) && $type == 'search') {
            $games = $this->_search_data($word);
        } else {
            $games = $this->_best_games($word);
        }

        $full = false;

        $html = view('games.url_post', compact('games', 'full'))->render();

        $show_more = $games->currentPage() != $games->lastPage();

        return response()->json(compact('success', 'html', 'current_page', 'show_more'), 200);
    }

    /**
     * @param string $word
     * @return object
     */
    private function _search_data(string $word): object
    {
        $query = Game::join('games_langs', 'games.id', '=', 'games_langs.games_id')
//            ->where('games_langs.lang_id', $this->current_locale->id)
            ->where('games.public', 1)
            ->where('games_langs.name', 'LIKE', '%' . $word . '%');

        if (isMobileDevice()) {
            $query->orderBy('games.mobi', 'DESC');
        }

        return $query->orderBy('games.created_at', 'ASC')
            ->groupBy('games.id')
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game',
            'games.rating', 'games.created_at', 'games_langs.name', 'games.mobi')
            ->paginate($this->per_page);
    }

    /**
     * @param string $word
     * @return object
     */
    private function _best_games(string $word): object
    {
        $best_ids = Game::getGamesIdsOfGenryBest();
        return Game::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            //->where('games_langs.lang_id', $this->current_locale->id)
            ->where('games.public', 1)
            ->whereIn('games.id', $best_ids)
            ->orderBy('games.created_at', 'ASC')
            ->groupBy('games.id')
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game',
                'games.rating', 'games.created_at', 'games_langs.name')
            ->paginate($this->per_page);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function searchPrev(Request $request)
    {
        $word = trim(strip_tags($request->get('search')));
        if (!empty($word)) {
            Search::addWord($word);
            return redirect()->route('search', compact('word'));
        }

        return redirect()->back()->withInput(['search' => $word]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAjax()
    {

        $data = [];
        $word = trim(strip_tags(request()->get('query')));
        $langsArr = [];
        if($this->current_locale->id == 3){//uk(unset ru)
            $langsArr = [ 2, 3 ];
        }else{//ru en (unset uk)
            $langsArr = [ 1, 2 ];
        }

        $games = Game::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->whereIn('games_langs.lang_id', $langsArr)
            ->where('games.public', 1)
            ->where('games_langs.name', 'LIKE', '%' . $word . '%')
            ->take(3)
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game',
                'games.rating', 'games.created_at', 'games_langs.name')
            ->get();

        foreach ($games as $game) {
            $data[] = [
                'value' => $game->name,
                'data'  => [
                    'url'         => route('seo.url', ['slug' => $game->url]),
                    'image'       => $game->getImage(),
                ]
            ];
        }

        return response()->json(['query' => $word, 'suggestions' => $data], 200);
    }
}
