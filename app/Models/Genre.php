<?php
/**
 * Created by Artdevue.
 * User: artdevue - Genre.php
 * Date: 2020-01-02
 * Time: 11:27
 * Project: gamesgo.club
 */

namespace App\Models;


use App\Facades\Glide;
use App\Http\Controllers\GameController;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Genre extends Model
{
    use ImageTrait;

    public static $out_ids = [];

    protected $fillable = [
        'pid', 'old_id', 'url', 'rating', 'sort', 'created_by', 'updated_by', 'public', 'show_menu', 'show_menug', 'popular', 'for_two', 'best_games', 'image', 'show_footer'
    ];

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
            $seo_url = SeoUrl::whereTable('genres')->wherePageId($model->id)->first();
            if ($seo_url) {
                $seo_url->delete();
            }
        });
    }

    public static function rewriteUrl($model)
    {
        $array_excl = [174, 201, 202, 207, 6, 7, 8, 9, 10, 11, 12, 13];
        if (!empty($model->id) && !in_array($model->id, $array_excl)) {
            // Get SeoUrl
            $seo_url = SeoUrl::whereTable('genres')->wherePageId($model->id)->first() ?? new SeoUrl(['table' => 'genres', 'page_id' => $model->id]);
            $seo_url->url = $model->url;
            $seo_url->save();
        }
    }

    public function getUrlByLang($lang)
    {
        return LaravelLocalization::localizeUrl(route('seo.url', ['slug' => $this->url]), $lang);
    }

    public static function getGamesGenre($url)
    {
        $page = Page::where(['url' => $url])->first();
        $games = new GameController();
        return $games->modelGamesHeroGenryTop(true, 'genre', $page->genre_id);
    }

    /**
     * Set the genre's alias.
     *
     * @param string $value
     * @return void
     */
    public function setUrlAttribute(string $value)
    {
        if (!empty($value)) {
            $this->attributes['url'] = Str::slug($value);
        }
    }

    /**
     * @param null $lang_id
     * @return mixed
     */
    public function getName(int $lang_id = null): string
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        // Get Name of language
        $genre_name = GenreLang::where('lang_id', $lang_id)->where('genre_id', $this->id)->value('value');

        if (!$genre_name) {
            $genre_name = GenreLang::where('lang_id', Language::getMain()->id)->where('genre_id', $this->id)->value('value');
        }

        return $genre_name;
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

        $genre_lang = GenreLang::where('lang_id', $lang_id)->where('genre_id', $this->id)->first();

        if (!$genre_lang) {
            $genre_lang = new GenreLang(['lang_id' => $lang_id, 'genre_id' => $this->id]);
        }

        return $genre_lang;
    }

    /**
     * Return image url for site
     *
     * @return mixed
     */
    public function getUrlImage(): string
    {
        if (!empty($this->image) && Storage::disk('images')->exists($this->image)) {
            return Storage::disk('images')->url($this->image);
        }

        return config('site.genres.image.default');
    }

    public function getImageByParams($params): string
    {
        return $this->getCroppedImageUrl($params, $this->image, config('site.genres.image.default'));
    }

    /**
     * Return count games for genry
     *
     * @return int
     */
    public function getCountGames(): int
    {
        $parents_genre = self::getParentGenreId($this->id, 0);

        return GamesGenre::whereIn('genry_id', $parents_genre)->groupBy('game_id')->get()->count();
    }

    /**
     * Return count of all games and mobi games
     *
     * @return string
     */
    public function getCountAllAndMobiGames(): string
    {
        $parents_genre = self::getParentGenreId($this->id, 0);

        $allGamesByGenre = GamesGenre::whereIn('genry_id', $parents_genre)
            ->groupBy('game_id')
            ->select('game_id')
            ->get()
            ->toArray();

        $gamesId = array_map(function ($item) {
            return $item['game_id'];
        }, $allGamesByGenre);

        $desktopGamesCount = Game::whereIn('games.id', $gamesId)
            ->where('public', 1)
            ->toBase()
            ->count();

        $mobiGamesCount = Game::whereIn('games.id', $gamesId)
            ->where('games.mobi', 1)
            ->where('public', 1)
            ->select('games.mobi')
            ->count();

        return "$desktopGamesCount/$mobiGamesCount";
    }

    /**
     * @param string $word
     * @param        $current_locale_id
     * @return object
     */
    public static function searchWord(string $word, $current_locale_id): object
    {
        return self::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            //->where('genre_langs.lang_id', $current_locale_id)
            ->where('genres.public', 1)
            ->where('genre_langs.value', 'LIKE', '%' . $word . '%')
            ->orderBy('genres.sort', 'ASC')
            ->groupBy('genres.id')
            ->take(config('site.menu.view_categories'))
            ->select('genres.image', 'genres.url', 'genre_langs.value as name')
            ->get();
    }

    /**
     * @param int $current_locale_id
     * @param int $limit
     * @return object
     */
    public static function getPopular(int $current_locale_id): object
    {
        return self::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', $current_locale_id)
            ->where('genres.public', 1)
            ->where('genres.popular', 1)
            ->orderBy('genres.sort', 'ASC')
            ->select('genres.image', 'genres.url', 'genre_langs.value as name')
            ->get();
    }

    /**
     * Out to menu
     *
     * @param int $current_locale_id
     * @param string $show_menu
     * @return object
     */
    public static function menuGamesCategory(int $current_locale_id, string $show_menu = 'show_menu'): object
    {
        return self::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', $current_locale_id)
            ->where('genres.public', 1)
            ->where('genres.' . $show_menu, 1)
            ->orderBy('genres.sort', 'ASC')
            ->take(config('site.menu.view_categories'))
            ->select('genres.image', 'genres.url', 'genre_langs.value as name')
            ->get();
    }

    /**
     * Out to menug
     *
     * @param int $current_locale_id
     * @return object
     */
    public static function menugGamesCategory(int $current_locale_id): object
    {
        return self::menuGamesCategory($current_locale_id, 'show_menug');
    }

    /**
     * @param int $game_id
     * @param int $current_locale_id
     * @param bool $remove_main
     * @return Genre[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getViaGameId(int $game_id, int $current_locale_id, $remove_main = false): object
    {
        return self::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->join('games_genres', 'genres.id', '=', 'games_genres.genry_id')
            ->where('genre_langs.lang_id', $current_locale_id)
            ->where('genres.public', 1)
            ->where('games_genres.game_id', $game_id)
            ->where(function ($query) use ($remove_main) {

                // Get Remove categoties
                $remove_gategories = Page::whereNotNull('genre_id')->pluck('genre_id');
                if (count($remove_gategories) < 1) {
                    $remove_gategories = [99999999999];
                }
                if ($remove_main) {
                    $query->whereNotIn('genres.id', $remove_gategories);
                }
            })
            ->groupBy('genres.id')
            ->select('genres.image', 'genres.url', 'genre_langs.value as name')
            ->get();
    }

    /**
     * @param int $parent
     * @param int $show_menu
     * @return array
     */
    public static function getParentGenreId(int $parent = 0, int $show_menu = 1): array
    {
        self::$out_ids = [];

        self::_getParents($parent, $show_menu);

        return self::$out_ids;

    }

    /**
     * @param $parent
     * @param $show_menu
     */
    private static function _getParents(int $parent, int $show_menu)
    {
        array_push(self::$out_ids, $parent);
        $parents = self::wherePid($parent)->wherePublic(1)->where(function ($query) use ($show_menu) {
            if ($show_menu == 1) {
                $query->whereShowMenu(1);
            }
        })->pluck('id')->toArray();

        if (count($parents) > 0) {
            array_push(self::$out_ids, $parents);
            foreach ($parents as $par) {
                self::_getParents($par, $show_menu);
            }
        }
    }

    public function getPageRatingCount()
    {
        return $this->hasMany('App\Models\GenresRating', 'page_id', 'id')->get()->count();
    }
}