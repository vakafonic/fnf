<?php

namespace App\Http\Controllers;


use App\Http\Requests\GetHeaderModalRequest;
use App\Models\Genre;
use App\Models\Heroes;
use App\Models\Language;
use App\Models\Page;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ModalController extends Controller
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

    public function renderHeaderModalByTypeAndLanguage(GetHeaderModalRequest $request)
    {
        $type = mb_strtolower($request->input('type'));
        $lang = Language::find($request->input('locale_id'));
        if ($type == 'genres') {
            return view('modals._genres_header_modal', [
                'genres' => Genre::menugGamesCategory($lang->id),
                'lang' => $lang
            ])->render();
        } elseif ($type == 'heroes') {
            return view('modals._heroes_header_modal', [
                'heroes' => Heroes::menuGamesCategory($lang->id),
                'lang' => $lang
            ])->render();
        } elseif ($type == 'girls' || $type == 'boys' || $type == 'kids') {
            $page = Page::find($request->input('page_id'));
            switch ($request->input('items_type')) {
                case 'genres':
                    return view('modals._genres_header_modal', [
                        'genres' => $page->getCategories($lang->id),
                        'lang' => $lang,
                    ])->render();
                case 'heroes':
                    return view('modals._heroes_header_modal', [
                        'heroes' => Heroes::menuGamesCategory($lang->id, $page->id),
                        'lang' => $lang,
                    ])->render();
                case 'games':
                    $type = $type == 'small kids' ? 'kids' : $type;
                    return view('modals._games_header_modal', [
                        'games' => Genre::getGamesGenre($type),
                        'lang' => $lang,
                    ])->render();
            }
        }
    }
}