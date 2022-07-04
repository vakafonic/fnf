<?php
/**
 * Created by Artdevue.
 * User: artdevue - GamesController.php
 * Date: 2019-12-23
 * Time: 09:13
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\ButtonsPlay;
use App\Models\Developer;
use App\Models\EventsAdminAction;
use App\Models\Game;
use App\Models\GamesGenre;
use App\Models\GamesHeroe;
use App\Models\GamesLang;
use App\Models\GamesSimilar;
use App\Models\Genre;
use App\Models\GenreLang;
use App\Models\Heroes;
use App\Models\HeroesLang;
use App\Models\Language;
use App\Models\Page;
use App\Models\Seopage;
use App\Models\SeoUrl;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request, Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class GamesController extends Controller
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        parent::__construct();
    }

    public function import()
    {
        try {

            $dubl = [];
            foreach (Page::all() as $game) {

//                $seo_url = SeoUrl::whereTable('pages')->wherePageId($game->id)->first();
//                if ($seo_url) {
//                    $seo_url->url = $game->url;
//                    $seo_url->save();
//                } else {
//                    // Get dubl
//                    $seo_url_dubl = SeoUrl::whereUrl($game->url)->first();
//                    if($seo_url_dubl) {
//                        $dubl[] = [
//                            'id' => $game->id,
//                            'url' => $game->url
//                        ];
//                    } else {
//                        $seo_url = new SeoUrl();
//                        $seo_url->url = $game->url;
//                        $seo_url->table = 'pages';
//                        $seo_url->page_id = $game->id;
//                        $seo_url->save();
//                    }
//                }

//                $game_update = [];
//
//                if (Storage::disk('images')->exists($game->image)) {
//                    $img = Image::make(public_path() . '/storage/images/' . $game->image);
//                    $extension = $img->extension;
//                    Storage::disk('images')->move($game->image, 'games/' . $game->url . '/' . $game->url .'.' . $extension);
//                    $game_update['image'] = 'games/' . $game->url . '/' . $game->url .'.' . $extension;
//                }
//
//                if (Storage::disk('images')->exists($game->image_cat)) {
//                    $image_cat = Image::make(public_path() . '/storage/images/' . $game->image_cat);
//                    $extension = $image_cat->extension;
//                    Storage::disk('images')->move($game->image_cat, 'games/' . $game->url . '/cat_' . $game->url .'.' . $extension);
//                    $game_update['image_cat'] = 'games/' . $game->url . '/cat_' . $game->url .'.' . $extension;
//                }
//
//                if(count($game_update) > 0) {
//                    $game->update($game_update);
//                }

            }
            echo '<pre>';
            die(print_r($dubl));
die('update_all2');

            $img = Image::make(public_path() . '/storage/images/games/barbie-and-evil-perfumes/image.jpg');
            die(var_dump($img->filename));
            die(var_dump(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME)));
        } catch (\Exception $e) {
            // do task when error
            die($e->getMessage());
        }


        die('stop import url9');
    }

    /**
     * @param $game_id
     * @param $genry_id
     */
    private function _saveGenre(int $game_id, int $genry_id)
    {
        $game_genre = new GamesGenre();
        $game_genre->game_id = $game_id;
        $game_genre->genry_id = $genry_id;
        $game_genre->created_by = 1;
        $game_genre->save();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function index()
    {
        if (!$this->user->isRoleAction('games_view') && !$this->user->isRoleAction('games_viewonly')) {
            return $this->get403Code();
        }

        $users_game_obj = Game::join('users', 'games.created_by', '=', 'users.id')
            ->groupBy('games.created_by')
            ->pluck('users.name', 'users.id');
        //$users_game->prepend('Все пользователи');

        $users_game = [0 => 'Все пользователи'];
        foreach ($users_game_obj as $id => $usr_name) {
            $users_game[$id] = $usr_name;
        }

        $tree = json_encode($this->getTree());

        return view('manager.games.index', compact('users_game', 'tree'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getAll(Request $request)
    {
        if (!$this->user->isRoleAction('games_view') && !$this->user->isRoleAction('games_viewonly')) {
            return $this->get403Code();
        }

        // Get all criteria
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $order = $request->input('order', 'desc');
        $sort = $request->input('sort', 'created_at');
        $user = $request->input('user');
        $public = $request->input('public', 3);
        $search = $request->input('search', null);
        $genre_add = $request->input('genre_add', '');

        if ($this->user->role != 1 && $this->user->isRoleAction('games_viewonly')) {
            $user = $this->user->id;
        }

        $query = Game::query();

        // Query of genre
        $genre_add_array = array_map('intval', explode(',', $genre_add));

        if (count($genre_add_array) > 0 && $genre_add_array[0] != 0) {
            // Get IDs of Game from GameGenre
            $game_ids = GamesGenre::whereIn('genry_id', $genre_add_array)->groupBy('game_id')->pluck('game_id')->toArray();

            array_push($game_ids, 9999999999);

            $query->whereIn('games.id', $game_ids);
        }

        $query->rightJoin('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->join('users', 'games.created_by', '=', 'users.id');
        //->where('games_langs.lang_id', Language::getMain('id'));

        // Query of User
        if (!empty((int)$user)) {
            $query->where('games.created_by', (int)$user);
        }

        // Query of Public
        if ($public < 3) {
            $query->where('games.public', (int)$public);
        }

        // Query of Search
        if (!empty($search)) {
            $query->where('games_langs.name', 'LIKE', "%$search%");
        }

        $query->groupBy('games.id');

        $total = $query->get()->count();

        $query->orderBy(($sort == 'name' ? 'games_langs.' : 'games.') . $sort, $order)
            ->skip($offset)
            ->take($limit)
            ->select('games.id', 'games.image', 'games.url', 'games.public', 'games.created_at', 'games.created_by', 'games_langs.name', 'users.name as user_name');

        $games = $query->get();

        $rows = [];

        foreach ($games as $game) {
            $games_array = $game->toArray();
            $games_array['name'] = $game->getTitle(1);
            $games_array['sub_name'] = $game->getSubTitle();
            $games_array['created_at_format'] = $game->getCreatedAt();
            $rows[] = $games_array;
        }

        return response()->json(compact('rows', 'total'), 200);
    }

    /**
     * @param Game $game
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function postDetails(Game $game)
    {
        $success = true;

        $all_events = EventsAdminAction::whereBetween('events_admin_id', [7, 9])->whereSoursId($game->id)->orderBy('created_at', 'DESC')->take(5)->get();

        $html = view('manager.games.details', compact('all_events'))->render();

        return response()->json(compact('success', 'html'), 200);
    }

    /**
     * Update public Game
     *
     * @param Game $game
     * @param int  $show
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function postPublic(Game $game, $show)
    {
        if (!$this->user->isRoleAction('games_edit')) {
            return $this->get403Code();
        }

        $game->update(['public' => $show]);

        $message = $show == 1 ? 'Игра опубликованна' : 'Игра снята с публикации';

        EventsAdminAction::create(['events_admin_id' => 8, 'user_id' => $this->user->id, 'sours_id' => $game->id, 'description' => $message]);

        return response()->json(['success' => true], 200);
    }

    /**
     * @param Game $game
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Game $game)
    {
        $title = 'Создать игру';

        $game->heroes_add = '';
        $game->genre_add = '';
        $similars_game = [];
        $countSimilars = 0;

        $translator = Auth::user()->isRoleAction('games_translator') && $this->user->role == 2;

        if (!empty($game->id)) {

            if ($this->user->role != 1 && !$this->user->isRoleAction('games_edit') && !$this->user->isRoleAction('games_editonly')) {
                return $this->get403Code();
            }

            if ($this->user->role != 1 && $this->user->isRoleAction('games_editonly') && $game->created_by != $this->user->id) {
                return $this->get403Code();
            }

            $title = 'Редактировать игру';

            // Get Developer name
            if (!empty($game->developeer_id)) {
                $game->developer_name = Developer::whereId($game->developeer_id)->value('name');
            }

            $heroes_add = GamesHeroe::where('game_id', $game->id)->pluck('heroy_id')->toArray();
            $game->heroes_add = implode(',', $heroes_add);
            $genre_add = GamesGenre::where('game_id', $game->id)->pluck('genry_id')->toArray();
            $game->genre_add = implode(',', $genre_add);
            //Get game name
            $locale = Language::where('code', '=', app()->getLocale())->get()->toArray();
            $locale = reset($locale);
            $game->name = GamesLang::whereLangId(1)->whereGamesId($game->id)->value('name');
            $game->name_en = GamesLang::whereLangId(2)->whereGamesId($game->id)->value('name');
            //Get game name
            // Get Similars
            $similars_game = Game::getGamesOfSimilars(Language::getMain('id'), $game->id);
            if(count($similars_game) > 0){
                $countAdd = 1;
                foreach($similars_game as $key => $similar){
                    if($similar->id == $game->id){
                        $countAdd = 0;
                    }
                    $similars_game[$key]->value = GamesLang::whereLangId(1)->whereGamesId($similar->id)->value('name');
                    $similars_game[$key]->value_en = GamesLang::whereLangId(2)->whereGamesId($similar->id)->value('name');
                }
                $countSimilars = count($similars_game) + $countAdd;
            }
        } else {
            if ($this->user->role != 1 && !$this->user->isRoleAction('games_create')) {
                return $this->get403Code();
            }

            $game->sandbox = 1;
        }

        $game->comand = !empty($game->comands) ? unserialize($game->comands) : [];

        // Get Langs
        $langs = [];
        foreach (Language::all() as $lang) {
            $langs[$lang->code] = $game->langsArray($lang->id);
        }

        $genresAll = Genre::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
                            ->where('genre_langs.lang_id', '=', 1)
                            ->orderBy('genre_langs.value')
                            ->select('genres.id', 'genre_langs.value')
                            ->get()
                            ->toArray();

        if($game->id){
            $generalGenre = GamesGenre::join('genre_langs', 'games_genres.genry_id', '=', 'genre_langs.genre_id')
                                ->where('games_genres.game_id', '=', $game->id)
                                ->where('genre_langs.lang_id', '=', 1)
                                ->where('games_genres.general', '=', 1)
                                ->select('games_genres.genry_id')
                                ->get()
                                ->toArray();
            if(!empty($generalGenre)){
                $generalGenre = reset($generalGenre)['genry_id'];
            } else {
                $generalGenre = 0;
            }
        } else {
            $generalGenre = 0;
        }

        $tree = json_encode($this->getTree());

        $heroes_tree = json_encode(Heroes::tree(1));

        return view('manager.games.create', compact('title', 'game', 'tree', 'heroes_tree', 'langs', 'similars_game', 'countSimilars', 'translator', 'genresAll', 'generalGenre'));
    }

    /**
     * Create or Edit Game
     *
     * @param Game    $game
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function putCreate(Game $game, Request $request)
    {
        $input_all = $request->except('_method', '_token');
        //array_walk($input_all, 'trim');
        $default_lang_code = Language::getMain('code');
        $old_image = $game->image;
        $old_image_cat = $game->image_cat;
        $genres_array = array_map('intval', explode(',', $input_all['genre_add']));
        if(!in_array((int)$request->input('general_category'), $genres_array)){
            return redirect($request->url())->withInput()->with('error', 'Главная категория должна быть выбрана из отмеченных категорий');
        }
        if (Auth::user()->isRoleAction('games_translator') && $this->user->role == 2) {
            $message = 'Что-то пошло не так. Ошибка при сохранении.';
            if (!empty($game->id)) {
                // Save Languages
                $array_filds = ['description', 'how_play'];
                foreach (Language::all() as $lang) {
                    if($lang->id != 1) {
                        $language = $game->langsArray($lang->id);
                        foreach ($array_filds as $fild) {
                            $language->$fild = $input_all[$fild . '_' . $lang->code];
                        }
                        $language->save();
                    }
                }

                $game->comands = serialize($request->input('comand', []));
                $game->save();

                $message = 'Сделан перевод игры ';

                EventsAdminAction::create(['events_admin_id' => 10, 'user_id' => $this->user->id, 'sours_id' => $game->id, 'description' => $message ]);

                if (!empty($input_all['save_exit']) && $input_all['save_exit'] == 'save') {
                    return redirect()->route('m.games.edit', ['game' => $game->id])->with('success', ' игра ' . $message);
                }

                return redirect()->route('m.games.index')->with('success', ' игра ' . $message);

            } else {
                $message = 'Игра должна быть созданна';
            }

            return redirect($request->url())->withInput()->with('error', $message);
        }

        if (empty($input_all['url'])) {
            $input_all['url'] = $input_all['name_' . $default_lang_code];
        }

        $input_all['url'] = Str::slug(trim($input_all['url']));
        $input_all['developer_name'] = strip_tags(trim($input_all['developer_name']));

        if (!empty($game->id)) {
            if ($this->user->role != 1 && !$this->user->isRoleAction('games_edit') && !$this->user->isRoleAction('games_editonly')) {
                return $this->get403Code();
            }

            if ($this->user->role != 1 && $this->user->isRoleAction('games_editonly') && $game->created_by != $this->user->id) {
                return $this->get403Code();
            }
        } else {
            if ($this->user->role != 1 && !$this->user->isRoleAction('games_create')) {
                return $this->get403Code();
            }
        }
//        $imageValidator = Validator::make($request->all(), [
//            'image' => ['dimensions:max_width=300,max_height=300'],
//        ]);
//        if ($imageValidator->errors()->first()) {
//            return redirect($request->url())->withInput();
//        }

        $validator = Validator::make($input_all, [
            'url'                        => [
                'required',
                'alpha_dash',
                'max:255',
                !empty($game->id) ? Rule::unique('seo_url')->ignore($game->id, 'page_id') :  Rule::unique('seo_url'),
            ],
            'name_' . $default_lang_code => [
                'required',
                'max:255'
            ],
            'image' => [
                Rule::dimensions()->maxWidth(300)->maxHeight(300)
            ],
            'image_cat' => [
                Rule::dimensions()->maxWidth(300)->maxHeight(225)
            ]
        ]);

        if ($validator->fails()) {
            return redirect($request->url())->withInput()->withErrors($validator);
        }

        // Get Heroes array
        /*if (!isset($input_all['heroes_add']) || count($input_all['heroes_add']) < 1) {
            return redirect($request->url())->withInput()->with('error', 'Нужно выбрать хотя бы одного героя');
        }*/

        // Get Genries array
        /*if (!isset($input_all['genre_add']) || strlen($input_all['genre_add']) < 1) {
            return redirect($request->url())->withInput()->with('error', 'Нужно выбрать хотя бы один жанр');
        }*/

        $success_message_pre = 'создана';
        $events_id = 7;

        //return redirect()->route('m.games.index')->with('error', 'В разработке');

        try {
            $game->developeer_id = 0;
            // Get Developer
            if (!empty($input_all['developer_name'])) {
                $developer = Developer::whereName($input_all['developer_name'])->first();
                if (!$developer) {
                    // Create new developer
                    $developer = new Developer();
                    $developer->name = trim($input_all['developer_name']);
                    $developer->url = Str::slug($developer->name);

                    $dev_count = Developer::whereUrl($developer->url)->count();
                    if ($dev_count > 0) {
                        $developer->url += '_' . $dev_count;
                    }
                    $developer->created_by = $this->user->id;
                    $message_f = 'создан новый разработчик';
                    $events_admin_id = 5;
                    $developer->save();

                    // Set events of create user
                    EventsAdminAction::create(['events_admin_id' => $events_admin_id, 'user_id' => $this->user->id, 'sours_id' => $developer->id, 'description' => $message_f]);
                }

                $game->developeer_id = $developer->id;
            }


            $game->url = $input_all['url'];
            $game->size_width = $input_all['size_width'] == 1 ? 1 : 0;
            $game->width = $input_all['width'] ?? null;
            $game->height = $input_all['height'] ?? null;
            //$game->video = $input_all['video'];
            $game->iframe_url = strip_tags($input_all['iframe_url']);
            $game->url_site = strip_tags($input_all['url_site']);
            $game->link_game = strip_tags($input_all['link_game']);
            $game->mobi = $request->input('mobi') ? 1 : 0;
            $game->iphone = $request->input('iphone') ? 1 : 0;
            $game->horizontal = $request->input('horizontal') ? 1 : 0;
            $game->sandbox = $request->input('sandbox') ? 1 : 0;
            $game->target_blank = $request->input('target_blank') ? 1 : 0;
            $game->no_block_ad = $request->input('no_block_ad') ? 1 : 0;
            if (Auth::user()->isRoleAction('games_public')) {
                $game->public = $request->input('public') ? 1 : 0;
            }

            // ID-002 - add offset to iframe
            $game->iframe_offset_top = $input_all['iframe_offset_top'] > 0 ? (int)$input_all['iframe_offset_top'] : 0;
            $game->iframe_offset_right = $input_all['iframe_offset_right'] > 0 ? (int)$input_all['iframe_offset_right'] : 0;
            $game->iframe_offset_bottom = $input_all['iframe_offset_bottom'] > 0 ? (int)$input_all['iframe_offset_bottom'] : 0;
            $game->iframe_offset_left = $input_all['iframe_offset_left'] > 0 ? (int)$input_all['iframe_offset_left'] : 0;

            if($this->user->isRoleAction('games_manualrating')) {
                $game->top = $request->input('top') ? 1 : 0;
                $game->good = $request->input('good') ? 1 : 0;
            }

            $game->sort = (int)$request->input('sort', 0);
            $game->comands = serialize($request->input('comand', []));

            if (!empty($game->id)) {
                $game->updated_by = $this->user->id;
                $success_message_pre = 'обновлена';
                $events_id = 8;
            } else {
                $game->created_by = $this->user->id;
            }
            $game->save();

            // Save Languages
            $array_filds = [
                'name', 'description', 'how_play', 'seo_name', 'seo_description', 'size_width',
                'height', 'width', 'iframe_url', 'url_site', 'link_game', 'mobi', 'target_blank', 'no_block_ad',
                'sandbox'
            ];
            foreach (Language::all() as $lang) {
                $language = $game->langsArray($lang->id);
                foreach ($array_filds as $fild) {
                    switch ($fild) {
                        case 'size_width':
                            $language->$fild = $input_all[$fild . '_' . $lang->code] == 1 ? 1 : 0;
                            continue 2;
                        case 'height' || 'width':
                            $language->$fild = $input_all[$fild . '_' . $lang->code] ?? null;
                            continue 2;
                        case 'iframe_url' || 'url_site' || 'link_game':
                            $language->$fild = strip_tags($input_all[$fild . '_' . $lang->code]);
                            continue 2;
                        case 'mobi' || 'target_blank' || 'no_block_ad' || 'sandbox':
                            $language->$fild = $request->input($fild . '_' . $lang->code) ? 1 : 0;
                            continue 2;
                    }

                    $language->$fild = $input_all[$fild . '_' . $lang->code];
                }

                $language->save();
            }

            // Delete Genry
            GamesGenre::where('game_id', $game->id)->delete();
            // Save Genres
            $genres_array = array_map('intval', explode(',', $input_all['genre_add']));
            foreach ($genres_array as $item) {
                if($request->input('general_category') == $item){
                    GamesGenre::firstOrCreate([
                        'game_id' => $game->id, 'genry_id' => $item, 'general' => 1
                    ], [
                        'game_id' => $game->id, 'genry_id' => $item, 'created_by' => $this->user->id, 'general' => 1
                    ]);
                } else {
                    GamesGenre::firstOrCreate([
                        'game_id' => $game->id, 'genry_id' => $item, 'general' => 0
                    ], [
                        'game_id' => $game->id, 'genry_id' => $item, 'created_by' => $this->user->id, 'general' => 0
                    ]);
                }
            }
            // Delete all heroes
            GamesHeroe::where('game_id', $game->id)->delete();
            // Save Heroes
            $heroes_aaray = array_map('intval', explode(',', $input_all['heroes_add']));
            foreach ($heroes_aaray as $item) {
                if ((int)$item > 0) {
                    GamesHeroe::firstOrCreate([
                        'game_id' => $game->id, 'heroy_id' => $item
                    ], [
                        'game_id' => $game->id, 'heroy_id' => $item, 'created_by' => $this->user->id
                    ]);
                }
            }

            // Delete Similar
            $old_similar = GamesSimilar::whereIdGame($game->id)->get();
            if ($old_similar->count() > 0) {
                foreach ($old_similar as $item)
                    $item->delete();
            }

            if (!empty($input_all['similars']) && is_array($input_all['similars'])) {

                $similars = $input_all['similars'];
                //array_push($similars, $game->id);
                foreach ($similars as $key => $similar) {
                    // Delete Old similar
                    $old_similar = GamesSimilar::whereIdGame($similar['id'])->get();
                    if ($old_similar->count() > 0) {
                        foreach ($old_similar as $item)
                            $item->delete();
                    }
                }

                $start_key = 0;

                foreach($similars as $key => $similar){
                    if($similar['id'] != $game->id){
                        Game::whereId($similar['id'])->update(['top' => $game->top]);
                        Game::whereId($similar['id'])->update(['good' => $game->good]);
                    }
                    foreach ($similars as $save_sim) {
                        $game_similar = new GamesSimilar(['id_game' => (int)$similar['id'], 'id_game_s' => (int)$save_sim['id'], 'position' => (int)$save_sim['position']]);
                        $game_similar->save();
                        $start_key++;
                    }

                    $start_key++;
                }
                /*// Save Similar
                foreach ($input_all['similars'] as $similar) {
                    $game_similar = new GamesSimilar(['id_game' => $game->id, 'id_game_s' => (int)$similar]);
                    $game_similar->save();
                    //array_push($array_similars, ['id_game' => $game->id, 'id_game_s' => (int) $similar]);
                }*/

                //GamesSimilar::insertOrIgnore($array_similars);
            }

            // Create directory Genres of storage
            if (!Storage::disk('images')->exists('games/' . $game->url)) {

                Storage::disk('images')->makeDirectory('games/' . $game->url, 0775, true); //creates directory
            }

            $img_url = 'games/' . $game->url . '/';

            // Save images
            if ($request->hasFile('image')) {
                $file            = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename        = Str::slug(trim($file->getClientOriginalName())). '.' . $extension;

                Storage::disk('images')->putFileAs(
                    $img_url,
                    $file,
                    $filename
                );
                $game->image = $img_url . $filename;
            } elseif ($request->input('image_delete', 0) == 1) {
                $game->image = '';
            }

            // Save images Cat
            if ($request->hasFile('image_cat')) {
                $file            = $request->file('image_cat');
                $extension = $file->getClientOriginalExtension();
                $filename        = Str::slug(trim($file->getClientOriginalName())). '_с.' . $extension;

                Storage::disk('images')->putFileAs(
                    $img_url,
                    $file,
                    $filename
                );
                $game->image_cat = $img_url . $filename;
            } elseif ($request->input('image_cat_delete', 0) == 1) {
                $game->image_cat = '';
            }

            // Save Video
            if ($request->hasFile('video')) {
                $file            = $request->file('video');
                $extension = $file->getClientOriginalExtension();
                $filename        = Str::slug(trim($file->getClientOriginalName())). '_v.' . $extension;

                Storage::disk('images')->putFileAs(
                    $img_url,
                    $file,
                    $filename
                );
                $game->video = '/storage/images/' . $img_url . $filename;
            } elseif ($request->input('delete_video', 0) == 1){
                $game->video = '';
            }

            //$game->image = $this->_save_images($input_all['image'], $old_image, 'image', $game->url);

            // Save image of category
            //$game->image_cat = $this->_save_images($input_all['image_cat'], $old_image_cat, 'image_cat', $game->url);


            $game->save();

        } catch (\Exception $e) {
            // do task when error
            return redirect($request->url())->withInput()->with('error', $e->getMessage());
        }

        EventsAdminAction::create(['events_admin_id' => $events_id, 'user_id' => $this->user->id, 'sours_id' => $game->id, 'description' => $success_message_pre . ' игра']);

        if (!empty($input_all['save_exit']) && $input_all['save_exit'] == 'save') {
            return redirect()->route('m.games.edit', ['game' => $game->id])->with('success', ' игра ' . $success_message_pre);
        }

        return redirect()->route('m.games.index')->with('success', ' игра ' . $success_message_pre);
    }

    public function getVideoDuration(Request $request)
    {
        $getID3 = new \getID3;
        try {
            $file = $getID3->analyze($request->file("inputFile"));
            $duration = date('H:i:s', $file['playtime_seconds']);
        } catch (\Exception $exception) {
            $duration = false;
        }
        return response()->json(["duration" => $duration]);
    }

    /**
     * @param Game $game
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDelete(Game $game)
    {
        $success = true;
        $message = ' Игра удалена';
        $game_id = $game->id;

        if (!$this->user->isRoleAction('games_delete') && !$this->user->isRoleAction('games_deleteonly') && $this->user->role != 1) {
            $success = false;
            $message = 'У вас нет прав на данную операцию';
        }

        if ($this->user->isRoleAction('games_deleteonly') && $game->created_by != $this->user->id && $this->user->role != 1) {
            $success = false;
            $message = 'У вас нет прав на данную операцию';
        }

        if ($success) {
            // Delete all foto
            if (Storage::disk('images')->exists('games/' . $game->url)) {

                Storage::disk('images')->delete('games/' . $game->url);
            }

            try {
                $game->delete();
            } catch (\Exception $e) {
                // do task when error
                $message = $e->getMessage();
            }

            EventsAdminAction::create(['events_admin_id' => 9, 'user_id' => $this->user->id, 'sours_id' => $game_id, 'description' => $message]);
        }

        return response()->json(compact('success', 'message'), 200);
    }

    /**
     * @param int $game_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSimilar(Request $request)
    {
        $out = [];
        $search = trim($request->get('search'));

        if (!empty($search)) {
            //$game_ids = GamesSimilar::whereIdGame($game_id)->pluck('id_game_s')->toArray();
            //array_push($game_ids, $game_id);

            $array_search = [];
            array_push($array_search, $search);
            $str2 = '';
            if(strlen($search) > 3) {
                $str2 = trim(substr(substr($search, 0, -1), 1));
                array_push($array_search, $str2);
            }

            if(strlen($str2) > 3) {
                array_push($array_search, trim(substr(substr($str2, 0, -1), 1)));
            }

            $games = Game::join('games_langs', 'games.id', '=', 'games_langs.games_id')
                //->join('games_heroes', 'games.id', '=', 'games_heroes.game_id')
                ->where('games_langs.name', 'LIKE', "%$search%")
                //->where(function($q) use($array_search) {
                //    foreach ($array_search as $s) {
                //        $q->orWhere('games_langs.name', 'like', '%' . $s . '%');
                //    }
                //})
                //->where('games_langs.lang_id', Language::getMain('id'))
                //->whereNotIn('games.id', $game_ids)
                //->where('games.public', 1)
                ->groupBy('games.id')
                ->orderBy('games.created_at', 'ASC')
                //->take(config('site.menu.view_games'))
                ->select('games.id', 'games.image', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name', 'games_langs.lang_id')
                ->get();

            foreach ($games as $game) {
                $gameNameRu = GamesLang::whereLangId(1)->whereGamesId($game->id)->value('name');
                $gameNameEn = GamesLang::whereLangId(2)->whereGamesId($game->id)->value('name');
                if($game->lang_id == 1){
                    $out[] = ['id' => $game->id, 'value' => $gameNameRu, 'valueEn' => $gameNameEn, 'image' => $game->image];
                } else {
                    $out[] = ['id' => $game->id, 'value' => $gameNameEn, 'valueEn' => $gameNameRu, 'image' => $game->image];
                }
            }
        }

        return response()->json($out, 200);
    }

    /**
     * @param int $lang
     * @return mixed
     */
    public function getTree($lang = 1)
    {
        $tree = new \Tree();

        $genre_all = Genre::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', 1)
            ->orderBy('genre_langs.value', 'ASC')
            ->select('genres.id', 'genres.pid', 'genre_langs.value')
            ->get();
        foreach ($genre_all as $item) {
            $tree->addItem(
                $item->id,
                $item->pid,
                [
                    'value' => $item->value
                ]
            );
        }
        return $this->createNode($tree, 0);
    }

    /**
     * @param $tree
     * @param $parent
     *
     * @return array|string
     */
    public function createNode($tree, $parent)
    {
        $s = [];
        if (!$tree->hasChilds($parent)) {
            return '';
        }

        $childs = $tree->getChilds($parent);

        foreach ($childs as $k => $v) {
            $p = ['id' => $v['id'], 'text' => $v['data']['value'], 'state' => ['opened' => false, 'selected' => false]];

            if ($tree->hasChilds($v['id'])) {
                $p['icon'] = 'fa fa-folder-open-o';
                $p['children'] = $this->createNode($tree, $v['id']);
            } else {
                $p['icon'] = 'fa fa-folder-o';
            }


            $s[] = $p;
        }

        return $s;
    }

    /**
     * @param string $image
     * @param        $string $old_image
     * @param        $string $field
     * @param        $string $game_url
     * @return string
     */
    private function _save_images($image, $old_image, $field, $game_url)
    {
        if (!empty($image) && $image == $old_image) {
            return $old_image;
        }

        if (!empty($image) && file_exists(public_path() . $image)) {
            $img = Image::make(public_path() . $image);

            $filename = Str::slug(trim($img->filename));


            if (config('site.game.' . $field . '.crop', false)) {
                $default_width = config('site.game.' . $field . '.width', 300);
                $default_height = config('site.game.' . $field . '.height', 300);
                $img->fit($default_width, $default_height, function ($constraint) {
                    $constraint->upsize();
                });
            }

            if (config('site.game.' . $field . '.watermark') && file_exists(public_path() . config('site.game.' . $field . '.patch_watermark'))) {
                $img->insert(public_path() . config('site.game.' . $field . '.patch_watermark'));
            }

            // Create directory Genres of storage
            if (!Storage::disk('images')->exists('games/' . $game_url)) {

                Storage::disk('images')->makeDirectory('games/' . $game_url, 0775, true); //creates directory
            }

            $extension = pathinfo(public_path($image), PATHINFO_EXTENSION);

            // Delete old image
            if (!empty($old_image) && Storage::disk('images')->exists($old_image)) {
                Storage::disk('images')->delete($old_image);
            }

            $img_url = 'games/' . $game_url . '/' . $filename . '.' . $extension;

            $img->save(Storage::disk('images')->path($img_url), config('site.image.' . $filename. '.quality', 100));

            return $img_url;
        }

        return '';
    }
}