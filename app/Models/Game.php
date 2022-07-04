<?php
/**
 * Created by Artdevue.
 * User: artdevue - Game.php
 * Date: 2020-01-05
 * Time: 00:03
 * Project: gamesgo.club
 */

namespace App\Models;


use App\Facades\Glide;
use App\Traits\ImageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use phpDocumentor\Reflection\Types\Object_;

class Game extends Model
{
    use ImageTrait;

    protected $fillable = [
        'old_id', 'url', 'developeer_id', 'size_width', 'video', 'image', 'image_cat', 'iframe_url', 'url_site',
        'link_game', 'mobi', 'iphone', 'horizontal', 'sandbox', 'target_blank', 'public', 'comands', 'sort', 'rating', 'best_game', 'visit',
        'game_likes', 'game_dislikes', 'created_at', 'created_by', 'updated_by',
        'iframe_offset_top', 'iframe_offset_right', 'iframe_offset_bottom', 'iframe_offset_left'
    ];

    public static function orderByLikes($queryBuilder)
    {
        $queryBuilder->orderBy('games.game_likes', 'DESC');
        return $queryBuilder;
    }

    public static function orderByNew($queryBuilder)
    {
        $queryBuilder->orderBy('games.created_at', 'DESC');
        return $queryBuilder;
    }

    public function calculateIframeStyles()
    {
        $style = '';

        $directions = ['top', 'right', 'bottom', 'left'];

        foreach ($directions as $direction) {
            $fieldname = 'iframe_offset_' . $direction;
            if(!empty($this->$fieldname)) {
                $style .= 'margin-' . $direction . ': -' . $this->$fieldname . '%;';
            }
        }

        return $style;
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            self::rewriteUrl($model);
        });

        self::updated(function ($model) {
            self::rewriteUrl($model);
        });

        self::deleted(function ($model) {
            $seo_url = SeoUrl::whereTable('games')->wherePageId($model->id)->first();
            if ($seo_url) {
                $seo_url->delete();
            }
        });
    }

    public static function rewriteUrl($model)
    {
        // Get SeoUrl
        $seo_url = SeoUrl::whereTable('games')->wherePageId($model->id)->first() ?? new SeoUrl(['table' => 'games', 'page_id' => $model->id]);
        $seo_url->url = $model->url;
        $seo_url->save();
    }

    /**
     * Set the game's alias.
     *
     * @param string $value
     * @return void
     */
    public function setUrlAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['url'] = Str::slug($value);
        }
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d-m-Y H:i:s');
    }

    /**
     * Get the comments for the game.
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * Get the comments for the game that can be viewed.
     */
    public function publicComments()
    {
        return $this->hasMany('App\Models\Comment')->where(['confirmed' => 1]);
    }

    /**
     * Get the comments for the game.
     */
    public function visits()
    {
        return $this->hasMany('App\Models\GamesVisit');
    }

    /**
     * Get the comments for the game.
     */
    public function unic()
    {
        return $this->hasMany('App\Models\GamesUnicUser');
    }

    /**
     * @return bool
     */
    public function isUserView(): bool
    {
        $ip_user = false;

        // Get User use IP
        if (!Auth::guest()) {
            $ip_user = GamesUnicUser::whereGameId($this->id)->whereUserId(Auth::user()->id)->exists();
        }

        $ip = request()->ip();

        if (!empty($ip) && $ip_user == false) {
            $ip_user = GamesUnicUser::whereGameId($this->id)->where(function ($query) {
                $query->whereUserId(0)
                    ->orWhereNull('user_id');
            })->whereIpUser($ip)->exists();
        }

        return $ip_user;
    }

    /**
     * @param null $category
     * @return bool
     */
    public function isBest(int $category = null)
    {
        if (is_null($category)) {
            return GenreBestGame::whereGameId($this->id)->exists();
        }

        // Get children
        $genries_ids = Genre::getParentGenreId($category);

        if (is_array($genries_ids) && count($genries_ids) > 0) {
            return GenreBestGame::whereGameId($this->id)->whereIn('genry_id', $genries_ids)->exists();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getIsNew()
    {
        $date = Carbon::parse($this->created_at);
        $now = Carbon::now();

        $diff = $date->diffInDays($now);

        return $diff <= config('site.game.new');
    }

    /**
     * @param null $lang_id
     * @return Model
     */
    public function langsArray(int $lang_id = null): object
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        $game_lang = GamesLang::where('lang_id', $lang_id)->where('games_id', $this->id)->first();

        if (!$game_lang) {
            $game_lang = new GamesLang(['lang_id' => $lang_id, 'games_id' => $this->id]);
        }

        return $game_lang;
    }

    /**
     * @param int $lang
     * @return string
     */
    public function getTitle(int $lang): ?string
    {
        return GamesLang::whereLangId($lang)->whereGamesId($this->id)->value('name');
    }

    /**
     * @return mixed
     */
    public function getSubTitle()
    {
        return GamesLang::whereLangId(2)->whereGamesId($this->id)->value('name');
    }

    /**
     * @return mixed
     */
    public function getImage(): string
    {
        if (!empty($this->image_cat) && Storage::disk('images')->exists($this->image_cat)) {
            return Storage::disk('images')->url($this->image_cat);
        }

        return config('site.game.image_cat.default');
    }

    public function getImageByParams($params): string
    {
        if($this->image_cat == null){
            $imageCat = '';
        } else {
            $imageCat = $this->image_cat;
        }
        return $this->getCroppedImageUrl($params, $imageCat, config('site.game.image_cat.default'));
    }

    /**
     * @return mixed
     */
    public function getMainImage(): string
    {
        if (!empty($this->image) && Storage::disk('images')->exists($this->image)) {
            return Storage::disk('images')->url($this->image);
        }

        return config('site.game.image_cat.default');
    }

    public function isWiew()
    {

    }

    /**
     * @param null $genry
     * @return array|\Illuminate\Support\Collection
     */
    public static function getGamesIdsOfGenryBest($genry = null)
    {
        $ids = [];

        if (is_null($genry)) {
            $ids = GenreBestGame::groupBy('game_id')->pluck('game_id');
        } else {
            $genries = Genre::getParentGenreId($genry, 0);
            if ($genries->count($genries) > 0) {
                $ids = GenreBestGame::whereIn('genry_id', $genries)->groupBy('game_id')->pluck('game_id');
            }
        }

        if (count($ids) == 0) {
            $ids = [9999999999];
        }
        return $ids;

    }

    /**
     * @param int $current_locale_id
     * @return object
     */
    public function getGenresOfGames(int $current_locale_id): object
    {
        return GamesGenre::join('genres', 'games_genres.genry_id', '=', 'genres.id')
            ->join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', $current_locale_id)
            ->where('games_genres.game_id', $this->id)
            ->groupBy('games_genres.genry_id')
            ->select('genres.id', 'genres.url', 'genre_langs.value as name')
            ->get();
    }

    /**
     * Out to menu best games
     *
     * @param $current_locale_id
     * @return Game[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function menu_best_games(int $current_locale_id): object
    {
        //$best_ids = self::getGamesIdsOfGenryBest();
        return self::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->where(function ($query) {
                if (isMobileDevice()) {
//                    $query->whereMobi(1);
                    $query->where('games.mobi', 1);
                }
            })
            //->whereIn('games.id', $best_ids)
            ->where('games.rating', '>', 0)
            ->orderBy('games.best_game', 'DESC')
            ->orderBy('games.rating', 'DESC')
            ->take(config('site.menu.view_games'))
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name', 'games.mobi')
            ->get();
    }

    /**
     * Out to menu best games
     *
     * @param $current_locale_id
     * @return Game[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function menu_top_games(int $current_locale_id): object
    {
        $game_ids = GamesGenre::getIdsOfGenresTopGood(Genre::all()->pluck('id')->toArray());
        if (count($game_ids) == 0) {
            $game_ids = [9999999999];
        }
        $query = Game::query();

        $query->rightJoin('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->whereIn('games.id', $game_ids);

        if (isMobileDevice()) {
            $query->orderBy('games.mobi', 'DESC');
        }

        $query->orderBy('games.top', 'DESC')->orderBy('games.good', 'DESC')->orderBy('games.rating', 'DESC');

        return $query->orderBy('games.created_at', 'DESC')
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game',
                'games.rating', 'games.created_at', 'games_langs.name', 'games.mobi')
            ->limit(10)
            ->get();
    }

    /**
     * Out to menu new games
     *
     * @param int $current_locale_id
     * @param int|null $limit
     * @return object
     */
    public static function menu_new_games(int $current_locale_id, int $limit = null): object
    {
        $limit = is_null($limit) ? config('site.menu.view_games') : $limit;

        return self::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->where(function ($query) {
                if (isMobileDevice()) {
//                    $query->whereMobi(1);
                    $query->where('games.mobi', 1);
                }
            })
            ->orderBy('games.created_at', 'ASC')
            ->take($limit)
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name')
            ->get();
    }

    /**
     * Out to menu new games of genries
     *
     * @param int $current_locale_id
     * @return object
     */
    public static function menu_new_games_genre(int $current_locale_id): object
    {
        return self::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->join('games_genres', 'games.id', '=', 'games_genres.game_id')
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->where(function ($query) {
                if (isMobileDevice()) {
//                    $query->whereMobi(1);
                    $query->where('games.mobi', 1);
                }
            })
            ->groupBy('games.id')
            ->orderBy('games.created_at', 'ASC')
            ->take(config('site.menu.view_games'))
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name')
            ->get();
    }

    /**
     * Out to menu new games of heroes
     *
     * @param int $current_locale_id
     * @return object
     */
    public static function menu_new_games_heroes(int $current_locale_id): object
    {
        return Game::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->join('games_heroes', 'games.id', '=', 'games_heroes.game_id')
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->where(function ($query) {
                if (isMobileDevice()) {
//                    $query->whereMobi(1);
                    $query->where('games.mobi', 1);
                }
            })
            ->groupBy('games.id')
            ->orderBy('best_game', 'DESC')
            ->orderBy('rating', 'DESC')
            ->orderBy('games.created_at', 'ASC')
            ->take(config('site.menu.view_games'))
            ->select('games.id', 'games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name')
            ->get();
    }

    /**
     * @param int $current_locale_id
     * @param int $genre_id
     * @return object
     */
    public static function menu_new_games_heroes_of_genre(int $current_locale_id, int $genre_id, $limit = null): object
    {
        $game_ids = GamesGenre::getIdsOfGenres(Genre::getParentGenreId($genre_id));

        return self::_getGamesOfGamesIds($current_locale_id, $game_ids, 'games.game_likes', $limit);
    }

    /**
     * @param int $current_locale_id
     * @param int $game_id
     * @param string $order
     * @param null $limit
     * @param null $ofset
     * @return object
     */
    public static function getGamesofGameId(int $current_locale_id, int $game_id, string $order = 'games.game_likes', $limit = null, $ofset = 0): object
    {
        $games = GamesGenre::getIdsOfGenres(GamesGenre::getIdsOfGame($game_id));
        $game_ids = array_diff($games, [$game_id]);

        return self::_getGamesOfGamesIds($current_locale_id, $game_ids, $order, $limit, $ofset);
    }

    /**
     * Returns public games with "top" OR "good" flag enabled, that have related genre or hero, ordered by next logic
     *  1) Matched by main game hero
     *  2) Matched by main game general category
     *  3) Matched by main game categories
     *
     * @param int $current_locale_id
     * @param int $game_id
     * @param null $limit
     * @param int $offset
     * @return object
     */
    public static function getRelatedGames(int $current_locale_id, int $game_id, $limit = null, $offset = 0): object
    {
//        dd($limit, $offset);
        return static::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->select(
                'games.id',
                'games.image_cat',
                'games.url',
                'games.video',
                'games.best_game',
                'games.rating',
                'games_langs.name',
                'games.mobi'
            )->leftJoin(
                DB::raw('(
                    SELECT gh.game_id game_id
                    FROM games_heroes gh
                    WHERE gh.heroy_id IN (
                        SELECT games_heroes.heroy_id
                        FROM games_heroes
                        WHERE games_heroes.game_id = ' . $game_id . '
                    )
                ) heroes_match'),
                'games.id',
                '=',
                'heroes_match.game_id'
            )->leftJoin(
                DB::raw('(
                    SELECT gg.game_id game_id
                    FROM games_genres gg
                    WHERE gg.genry_id IN (
                        SELECT games_genres.genry_id
                        FROM games_genres
                        WHERE games_genres.game_id =  ' . $game_id . ' AND games_genres.general = 1
                    )
                ) general_genre_match'),
                'games.id',
                '=',
                'general_genre_match.game_id'
            )->leftJoin(
                DB::raw('(
                    SELECT gg.game_id
                    FROM games_genres gg
                    WHERE gg.genry_id IN (
                        SELECT games_genres.genry_id
                        FROM games_genres
                        WHERE games_genres.game_id = ' . $game_id . '
                    )
                ) genre_match'),
                'games.id',
                '=',
                'genre_match.game_id'
            )
            ->where(function($query) {
                $query->where('games.top', '=', 1)
                      ->orWhere('games.good', '=', 1);
            })
            ->where(function($query) {
                $query->whereNotNull('heroes_match.game_id')
                      ->orWhereNotNull('general_genre_match.game_id')
                      ->orWhereNotNull('genre_match.game_id');
            })
            ->where('games.id', '!=', $game_id)
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->where(function ($query) {
                if (isMobileDevice()) {
                    $query->where('games.mobi', 1);
                }
            })
            ->groupBy('games.id')
            ->orderByRaw('CASE
               WHEN heroes_match.game_id IS NOT NULL THEN 3
               WHEN general_genre_match.game_id IS NOT NULL THEN 2
               WHEN genre_match.game_id IS NOT NULL THEN 1
            END DESC, games_langs.name ASC')
            ->skip($offset)
            ->take($limit ?? config('site.menu.view_games'))
            ->get();
    }


    /* new */
    /**
     * @param int $current_locale_id
     * @param array $game_ids
     * @return object
     */
    public static function getGamesOfIdsGames(int $current_locale_id, array $game_ids): object
    {
        return self::_getGamesOfGamesIds($current_locale_id, $game_ids);
    }

    /**
     * @param int $current_locale_id
     * @param int $game_id
     * @return object
     */
    public static function getGamesOfSimilars(int $current_locale_id, int $game_id): object
    {
        $game_ids = GamesSimilar::whereIdGame($game_id)->orderBy('position', 'ASC')->pluck('id_game_s')->toArray();
        return self::_getGamesOfGamesIds($current_locale_id, $game_ids, 'games.game_likes', 1000, 0, $game_id);
    }

    /**
     * @param int $current_locale_id
     * @param array $game_ids
     * @param string $order
     * @param null|int $limit
     * @return object
     */
    private static function _getGamesOfGamesIds(int $current_locale_id, array $game_ids, string $order = 'games.created_at', $limit = null, $ofset = 0, $game_id = null): object
    {
        $games = Game::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->join('games_similars', 'games.id', '=', 'games_similars.id_game_s')
            ->where('games_langs.lang_id', $current_locale_id)
            //->where('games_similars.id_game', $game_id)
            ->whereIn('games.id', $game_ids)
            ->where('games.public', 1)
            ->where(function ($query) {
                if (isMobileDevice()) {
                    $query->where('games.mobi', 1);
                }
            })
            ->orderBy('games_similars.position', 'ASC')
            ->groupBy('games.id')
            ->skip($ofset)
            ->take($limit ?? config('site.menu.view_games'))
            ->select('games_similars.id_game_s as id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name', 'games.mobi')
            ->get();

            return $games;
    }
    /* new */
    private static function _getGamesOfGamesIdsTopGood(int $current_locale_id, array $game_ids, $limit = null, $offset = 0): object
    {
        $games = Game::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->select('games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name', 'games.mobi')
            ->where('games_langs.lang_id', $current_locale_id)
            ->whereIn('games.id', $game_ids)
            ->where('games.public', 1)
            ->where(function ($query) {
                if (isMobileDevice()) {
                    $query->where('games.mobi', 1);
                }
            })
            ->orderByRaw('CASE
                           WHEN id.PinRequestCount <> 0 THEN 5
                           WHEN id.HighCallAlertCount <> 0 THEN 4
                           WHEN id.HighAlertCount <> 0 THEN 3
                           WHEN id.MediumCallAlertCount <> 0 THEN 2
                           WHEN id.MediumAlertCount <> 0 THEN 1
                        END DESC'
            )
//            ->orderBy('games.top', 'DESC')
//            ->orderBy('games.good', 'DESC')
//            ->orderBy('games.rating', 'DESC')
            ->skip($offset)
            ->take($limit ?? config('site.menu.view_games'))
            ->get();

        return $games;
    }


    /* new */
    /**
     * @return bool
     */
    public function getLastUserComment(): bool
    {
        $date_old = date('Y-m-d H:i:s', strtotime('-5 minutes'));

        // Get las comment of User use ID
        if (!Auth::guest()) {
            return Comment::whereGameId($this->id)->whereUserId(Auth::user()->id)->where('created_at', '>', $date_old)->exists();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip)) {

            return Comment::whereGameId($this->id)->where(function ($query) {
                $query->whereUserId(0)
                    ->orWhereNull('user_id');
            })->whereIpUser($ip)->where('created_at', '>', $date_old)->exists();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function sheckUserLike()
    {
        if (!Auth::guest()) {
            return GamesLike::whereGameId($this->id)->whereUserId(Auth::user()->id)->exists();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip)) {

            return GamesLike::whereGameId($this->id)->where(function ($query) {
                $query->whereUserId(0)
                    ->orWhereNull('user_id');
            })->whereIpUser($ip)->exists();
        }

        return false;
    }

    public function checkUserLike()
    {
        if (!Auth::guest()) {
            return GamesLike::whereGameId($this->id)->whereUserId(Auth::user()->id)->where('game_like', '=', 1)->exists();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip)) {

            return GamesLike::whereGameId($this->id)->where(function ($query) {
                $query->whereUserId(0)
                    ->orWhereNull('user_id')
                    ->where('game_like', '=', 1);
            })->whereIpUser($ip)->exists();
        }

        return false;
    }

    public function checkUserDislike()
    {
        if (!Auth::guest()) {
            return GamesLike::whereGameId($this->id)->whereUserId(Auth::user()->id)->where('game_like', '=', 0)->exists();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip)) {

            return GamesLike::whereGameId($this->id)->where(function ($query) {
                $query->whereUserId(0)
                    ->orWhereNull('user_id')
                    ->where('game_like', '=', 0);
            })->whereIpUser($ip)->exists();
        }

        return false;
    }

    public function getUrlByLang($lang)
    {
        return LaravelLocalization::localizeUrl(route('seo.url', ['slug' => $this->url]), $lang);
    }

    /**
     * @return \stdClass
     */
    public function getUserLike()
    {
        $out = new \stdClass();
        $out->like = false;
        $out->dislike = false;

        // Get IP user
        $ip = request()->ip();

        $likes = false;

        if (!Auth::guest()) {
            $likes = GamesLike::whereGameId($this->id)->whereUserId(Auth::user()->id)->first();
        } elseif (!empty($ip)) {
            $likes = GamesLike::whereGameId($this->id)->whereNull('user_id')->whereIpUser($ip)->first();
        }

        if ($likes) {
            if ($likes->game_like == 1) {
                $out->like = true;
            } else {
                $out->dislike = true;
            }
        }

        return $out;
    }

    /**
     * @return array
     */
    public function updateLikes()
    {
        $this->game_likes = GamesLike::whereGameId($this->id)->whereGameLike(1)->count();
        $this->game_dislikes = GamesLike::whereGameId($this->id)->whereGameLike(0)->count();
        $this->save();

        return ['likes' => $this->game_likes, 'dislikes' => $this->game_dislikes];
    }

    /**
     * @return bool
     */
    public function setVizit()
    {
        if ($vizit = $this->checkVizit()) {
            $vizit->vizit = $vizit->vizit + 1;
            $vizit->save();
            return true;
        }

        $visit = new GamesVisit();
        $visit->game_id = $this->id;
        if (!Auth::guest()) {
            $visit->user_id = Auth::user()->id;
        }
        $visit->ip_user = request()->ip();
        return $visit->save();
    }

    /**
     * @return bool
     */
    public function checkVizit()
    {
        if (!Auth::guest()) {
            return GamesVisit::whereGameId($this->id)->whereUserId(Auth::user()->id)->first();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip)) {

            return GamesVisit::whereGameId($this->id)->where(function ($query) {
                $query->whereUserId(0)
                    ->orWhereNull('user_id');
            })->whereIpUser($ip)->first();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkFavorite()
    {
        if (!Auth::guest()) {
            return GameFavorite::whereGameId($this->id)->whereUserId(Auth::user()->id)->first();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip)) {

            return GameFavorite::whereGameId($this->id)->where(function ($query) {
                $query->whereUserId(0)
                    ->orWhereNull('user_id');
            })->whereIpUser($ip)->first();
        }

        return false;
    }

    public function getPageRatingCount()
    {
        return $this->hasMany('App\Models\PagesRating', 'page_id', 'id')->get()->count();
    }

    /**
     * @param int $current_locale_id
     * @return object
     */
    public static function getFavorites(int $current_locale_id): object
    {
        return self::join('game_favorites', 'games.id', '=', 'game_favorites.game_id')
            ->join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->where(function ($query) {

                if (isMobileDevice()) {
//                    $query->whereMobi(1);
                    $query->where('games.mobi', 1);
                }

                if (!Auth::guest()) {
                    $query->where('game_favorites.user_id', Auth::user()->id);
                }

                $ip = request()->ip();
                if (!empty($ip)) {
                    $query->where('game_favorites.ip_user', $ip);
                }
            })
            ->groupBy('games.id')
            ->orderBy('game_favorites.created_at', 'DESC')
            ->take(config('site.count_favorites'))
            ->select('games.id', 'games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name', 'games.mobi')
            ->get();
    }

    /**
     * @param int $current_locale_id
     * @param int|null $limit
     * @return object
     */
    public static function getViews(int $current_locale_id, int $limit = null): object
    {
        $limit = is_null($limit) ? config('site.count_views') : $limit;
        $result = self::join('games_visits', 'games.id', '=', 'games_visits.game_id')
            ->join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->where(function ($query) {

                if (isMobileDevice()) {
                    $query->where('games.mobi', 1);
                }

                if (!Auth::guest()) {
                    $query->where('games_visits.user_id', Auth::user()->id);
                }

                $ip = request()->ip();
                if (!empty($ip)) {
                    $query->where('games_visits.ip_user', $ip);
                }
            })
            ->groupBy('games.id')
            ->orderBy('games_visits.created_at', 'DESC')
            ->take($limit)
            ->select('games.id', 'games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name', 'games.mobi')
            ->get();
        return $result;
    }


    public static function getGamesDefaultScopeQuery(int $current_locale_id, int $limit = null)
    {
        return static::join('games_langs', 'games.id', '=', 'games_langs.games_id')
            ->select('games.id', 'games.id', 'games.image_cat', 'games.url', 'games.video', 'games.best_game', 'games.rating', 'games_langs.name', 'games.mobi')
            ->where('games_langs.lang_id', $current_locale_id)
            ->where('games.public', 1)
            ->take($limit)
            ->groupBy('games.id');
    }

    public static function orderByBest($queryBuilder)
    {
        $queryBuilder->orderBy('games.top', 'DESC');
        $queryBuilder->orderBy('games.good', 'DESC');
        $queryBuilder->orderBy('games.game_likes', 'DESC');
        return $queryBuilder;
    }
}
