<?php
/**
 * Created by Artdevue.
 * User: artdevue - Heroes.php
 * Date: 2020-01-04
 * Time: 14:26
 * Project: gamesgo.club
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Heroes extends Model
{
    protected $table = 'heroes';

    protected $fillable = [
        'old_id', 'ur', 'image', 'public', 'show_menu', 'rating', 'sort', 'count_game', 'created_by', 'updated_by', 'show_footer'
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
            $seo_url = SeoUrl::whereTable('heroes')->wherePageId($model->id)->first();
            if ($seo_url) {
                $seo_url->delete();
            }
        });
    }

    public static function rewriteUrl($model)
    {
        // Get SeoUrl
        $seo_url = SeoUrl::whereTable('heroes')->wherePageId($model->id)->first() ?? new SeoUrl(['table' => 'heroes', 'page_id' => $model->id]);
        $seo_url->url = $model->url;
        $seo_url->save();
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

    public function getUrlByLang($lang)
    {
        return LaravelLocalization::localizeUrl(route('seo.url', ['slug' => $this->url]), $lang);
    }

    public function langs()
    {
        return $this->hasMany('App\Models\HeroesLang', 'heroes_id', 'id');
    }

    /**
     * @param null $lang_id
     * @return GenreLang|Model|null
     */
    public function langsArray(int $lang_id = null): object
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        $genre_lang = HeroesLang::where('langs_id', $lang_id)->where('heroes_id', $this->id)->first();

        if (!$genre_lang) {
            $genre_lang = new HeroesLang(['langs_id' => $lang_id, 'heroes_id' => $this->id]);
        }

        return $genre_lang;
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
        $heroes_name = HeroesLang::where('langs_id', $lang_id)->where('heroes_id', $this->id)->value('value');

        if (!$heroes_name) {
            $heroes_name = HeroesLang::where('langs_id', Language::getMain()->id)->where('heroes_id', $this->id)->value('value');
        }

        return $heroes_name;
    }

    /**
     * Return count games for heroy
     *
     * @return int
     */
    public function getCountGames(): int
    {
        $this->count_game = GamesHeroe::whereHeroyId($this->id)->groupBy('game_id')->get()->count();
        $this->save();
        return $this->count_game;
    }

    /**
     * Return count of all games and mobi games
     *
     * @return string
     */
    public function getCountMobiAndAllGames(): string
    {
        $allGamesByHero = GamesHeroe::whereHeroyId($this->id)
            ->groupBy('game_id')
            ->select('game_id')
            ->get()
            ->toArray();

        $gamesId = array_map(function ($item) {
            return $item['game_id'];
        }, $allGamesByHero);

        $desktopGamesCount = Game::whereIn('games.id', $gamesId)
            ->where('public', 1)
            ->toBase()
            ->count();

        $mobiGamesCount = Game::whereIn('games.id', $gamesId)
            ->where('public', 1)
            ->where('games.mobi', 1)
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
        return self::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            //->where('heroes_langs.langs_id', $current_locale_id)
            ->where('heroes.public', 1)
            ->where('heroes_langs.value', 'LIKE', '%' . $word . '%')
            ->orderBy('heroes.sort', 'ASC')
            ->groupBy('heroes.id')
            ->take(config('site.menu.view_categories'))
            ->select('heroes.image', 'heroes.url', 'heroes_langs.value as name')
            ->get();
    }

    /**
     * Return heroes array from tree view
     *
     * @param null $lang_id
     * @return array
     */
    public static function tree(int $lang_id = null): array
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        $heroes_array = [];

        $heroes_all = parent::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->where('heroes_langs.langs_id', $lang_id)
            ->orderBy('heroes_langs.value', 'ASC')
            ->select('heroes.id', 'heroes_langs.value as name')
            ->get();

        foreach ($heroes_all as $item) {
            $heroes_array[] = [
                'id' => $item->id,
                'text' => $item->name,
                'icon' => 'fa fa-folder-',
                'state' => [
                    'opened' => false,
                    'selected' => false
                ]
            ];
        }

        $heroes_array = [
            'id' => 0,
            'text' => __('main.heroes_all'),
            'icon' => 'fa fa-folder-open-o',
            'children' => $heroes_array,
            'state' => [
                'opened' => true,
                'selected' => false
            ]
        ];

        return $heroes_array;
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

        return config('site.heroes.image.default');
    }

    public function getPageRatingCount()
    {
        return $this->hasMany('App\Models\HeroesRating', 'page_id', 'id')->get()->count();
    }

    /**
     * @param int $current_locale_id
     * @return Heroes[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function menuGamesCategory(int $current_locale_id, int $page = 5): ?object
    {
        return self::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->join('page_heroes', 'heroes.id', '=', 'page_heroes.id_heroy')
            ->where('page_heroes.id_page', $page)
            ->where('heroes_langs.langs_id', $current_locale_id)
            ->where('heroes.public', 1)
            ->orderBy('page_heroes.sort', 'ASC')
            ->groupBy('heroes.id')
            ->take(config('site.menu.view_categories'))
            ->select('heroes.image', 'heroes.url', 'heroes_langs.value as name')
            ->get();
    }

    /**
     * @param int      $current_locale_id
     * @param int|null $genre_id
     * @return Heroes[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function menuGamesCategoryGenre(int $current_locale_id, int $genre_id = null): object
    {
        // Get Games Ids of Genry
        $game_ids = GamesGenre::getIdsOfGenres(Genre::getParentGenreId($genre_id));
        if (count($game_ids) == 0) {
            $game_ids = ['9999999999'];
        }

        return self::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->join('games_heroes', 'heroes.id', '=', 'games_heroes.heroy_id')
            ->whereIn('games_heroes.game_id', $game_ids)
            ->where('heroes_langs.langs_id', $current_locale_id)
            ->where('heroes.public', 1)
            ->where('heroes.show_menu', 1)
            ->orderBy('heroes.sort', 'ASC')
            ->groupBy('heroes.id')
            ->take(config('site.menu.view_categories'))
            ->select('heroes.image', 'heroes.url', 'heroes_langs.value as name')
            ->get();


    }

    /**
     * @param int $game_id
     * @param int $current_locale_id
     * @return Genre[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getViaGameId(int $game_id, int $current_locale_id)
    {
        return self::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->join('games_heroes', 'heroes.id', '=', 'games_heroes.heroy_id')
            ->where('heroes_langs.langs_id', $current_locale_id)
            ->where('heroes.public', 1)
            ->where('games_heroes.game_id', $game_id)
            ->groupBy('heroes.id')
            ->select('heroes.image', 'heroes.url', 'heroes_langs.value as name')
            ->get();
    }
}