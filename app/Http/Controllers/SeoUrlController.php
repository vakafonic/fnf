<?php
/**
 * Created by Artdevue.
 * User: artdevue - SeoUrlController.php
 * Date: 2020-03-26
 * Time: 09:26
 * Project: gamesgo.club
 */

namespace App\Http\Controllers;


use App\Models\Game;
use App\Models\Genre;
use App\Models\Heroes;
use App\Models\Page;
use App\Models\SeoUrl;
use Illuminate\Http\Request;

class SeoUrlController extends Controller
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

    public function geRead(string $slug, int $page = null, Request $request)
    {
        if(!is_null($page)) {
            request()->merge([
                'page' => $page,
            ]);
        }

        // ID-001 filter games not only by genre, but with device
        /** @var Page|null $pageModel */
        $pageModel = Page::where('url', $slug)->first();
        if ($pageModel !== null && $pageModel->filter_type !== Page::FILTER_TYPE_GENRE) {
            $c = new GameController();
            return $c->getGamesHeroGenry('filter', $slug, $request);
        }


        $genres = [];
        $genreAll = Genre::all()->toArray();
        foreach($genreAll as $genre){
            $genres[] = $genre['url'];
        }
        $games = [];
        $gameAll = Game::all()->toArray();
        foreach($gameAll as $game){
            $games[] = $game['url'];
        }
        $heroes = [];
        $heroesAll = Heroes::all()->toArray();
        foreach($heroesAll as $hero){
            $heroes[] = $hero['url'];
        }


//        if(!empty(request()->get('page'))) {
//            $status = '404';
//            return response()->view('errors.404', compact('status'))->setStatusCode(404);
//        }
        // If this url is best
        if(substr($slug, 0, 5) == 'best-') {
            $slugCut = substr($slug, 5);
            if(in_array($slugCut, $genres) || in_array($slugCut, $games) || in_array($slugCut, $heroes)){
                $seo_url = SeoUrl::whereUrl(substr($slug, 5))->first();
            } else {
                $seo_url = SeoUrl::whereUrl($slug)->first();
            }
        } else {
            $seo_url = SeoUrl::whereUrl($slug)->first();
        }

        if(is_null($seo_url)) {
            $status = '404';
            return response()->view('errors.404', compact('status'))->setStatusCode(404);
        }

        if(!is_null($page) && $page < 2) {
            return redirect(route('seo.url', ['slug' => $slug]), 301);
        }
        switch ($seo_url->table) {
            case 'pages':
                $page = new GameController();
                $array_excl = [6, 7, 8, 9, 10, 11, 12, 13];
                if(in_array($seo_url->page_id, $array_excl)) {
//                    $test=$page->getGamesHeroGenry('genre', $slug, request());
//                    dd($test);
                    return $page->getGamesHeroGenry('genre', $slug, request());
                }
                return $page->games($slug, request());
                break;
            case 'heroes':
                $page = new GameController();
                return $page->getGamesHeroGenry('hero', $slug, request());
                break;
            case 'genres':
                $page = new GameController();
                return $page->getGamesHeroGenry('genre', $slug, request());
                break;
            case 'games':
                $page = new GameController();
                return $page->getGame($slug);
                break;
            case 'seopages':
                $page = new SeopageController();
                return $page->getGames($slug, request());
                break;
        }

        $status = '404';
        return response()->view('errors.404', compact('status'))->setStatusCode(404);
    }
}