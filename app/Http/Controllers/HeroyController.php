<?php
/**
 * Created by Artdevue.
 * User: artdevue - HeroyController.php
 * Date: 2020-01-30
 * Time: 12:21
 * Project: gamesgo.club
 */

namespace App\Http\Controllers;


use App\Models\Heroes;
use App\Models\Page;
use Illuminate\Http\Request;

class HeroyController extends Controller
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

    public function index()
    {
        $page = Page::whereUrl('heroes')->wherePublic(1)->first();

        if (!$page) {
            return $this->get404Code();
        }
        $lang_page = $page->langsArray($this->current_locale->id);

        $games = Heroes::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->where('heroes_langs.langs_id', $this->current_locale->id)
            ->where('heroes.public', 1)
            ->orderBy('heroes.created_at', 'ASC')
            ->select('heroes_langs.value', 'heroes.url', 'heroes.image')
            ->get();
            //->paginate($this->per_page);

        $pages = Page::join('page_langs', 'pages.id', '=', 'page_langs.page_id')
            ->where('page_langs.lang_id', $this->current_locale->id)
            ->where('pages.public', 1)
            ->where('pages.filter_type', '!=', 1)
            ->select('pages.id as id','pages.url as url', 'page_langs.name as title')
            ->get();

        $html = view('genre_heroes_post', ['games' => $games, 'url' => 'hero', 'pages' => $pages])->render();
        //$show_more = $games->currentPage() != $games->lastPage();

        //return view('genre_heroes', compact('page', 'lang_page', 'html', 'show_more'));
        return view('genre_heroes', compact('page', 'lang_page', 'html'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function postIndex(Request $request)
    {
        $current_page = $request->get('page', 1);
        $success = true;

        $games = Heroes::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->where('heroes_langs.langs_id', $this->current_locale->id)
            ->where('heroes.public', 1)
            ->orderBy('heroes.created_at', 'ASC')
            ->select('heroes_langs.value', 'heroes.url', 'heroes.image')
            ->paginate($this->per_page);

        $html = view('genre_heroes_post', ['games' => $games, 'url' => 'hero'])->render();
        $show_more = $games->currentPage() != $games->lastPage();

        return response()->json(compact('success', 'html', 'current_page', 'show_more'), 200);
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getSlug(string $slug)
    {
        $hero = Heroes::whereUrl($slug)->wherePublic(1)->first();

        if (!$hero) {
            return $this->get404Code();
        }

        $hero_lang = $hero->langsArray($this->current_locale->id);

        return view('heroes.get', compact('hero', 'hero_lang'));
    }
}