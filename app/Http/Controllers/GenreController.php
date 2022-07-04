<?php
/**
 * Created by Artdevue.
 * User: artdevue - GenreController.php
 * Date: 2020-01-25
 * Time: 23:19
 * Project: gamesgo.club
 */

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Page;
use Illuminate\Http\Request;

class GenreController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $page = Page::whereUrl('genres')->wherePublic(1)->first();

        if (!$page)
        {
            return $this->get404Code();
        }
        $lang_page = $page->langsArray($this->current_locale->id);

        $games = Genre::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', $this->current_locale->id)
            ->where('genres.public', 1)
            ->orderBy('genres.created_at', 'ASC')
            ->select('genre_langs.value', 'genres.url', 'genres.image')
            ->get();
            //->paginate($this->per_page);

        $pages = Page::join('page_langs', 'pages.id', '=', 'page_langs.page_id')
            ->where('page_langs.lang_id', $this->current_locale->id)
            ->where('pages.public', 1)
            ->where('pages.filter_type', '!=', 1)
            ->select('pages.id as id','pages.url as url', 'page_langs.name as title')
            ->get();

        $html = view('genre_heroes_post', ['games' => $games, 'pages' => $pages, 'url' => 'genre'])->render();
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

        $games = Genre::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', $this->current_locale->id)
            ->where('genres.public', 1)
            ->orderBy('genres.created_at', 'ASC')
            ->select('genre_langs.value', 'genres.url', 'genres.image')
            ->paginate($this->per_page);

        $html = view('genre_heroes_post', ['games' => $games, 'url' => 'genre'])->render();
        $show_more = $games->currentPage() != $games->lastPage();

        return response()->json(compact('success', 'html', 'current_page', 'show_more'), 200);
    }

    /**
     * @param $slug
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getSlug($slug, Request $request)
    {
        $genre = Genre::whereUrl($slug)->wherePublic(1)->first();

        if (!$genre) {
            return $this->get404Code();
        }

        return view('genre.get', compact('genre', 'genre_lang'));
    }
}