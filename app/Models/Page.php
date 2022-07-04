<?php
/**
 * Created by Artdevue.
 * User: artdevue - Page.php
 * Date: 2020-01-23
 * Time: 00:30
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    public const FILTER_TYPE_GENRE = 1;
    public const FILTER_TYPE_DEVICE_IOS_ANDROID = 2;
    public const FILTER_TYPE_DEVICE_IOS = 3;
    public const FILTER_TYPE_DEVICE_ANDROID = 4;

    protected $fillable = [
        'url', 'show_top_menu', 'genre_id', 'filter_type', 'rating', 'sort', 'created_by', 'updated_by'
    ];

    public static function boot()
    {
        parent::boot();

        self::created(function($model){
            self::rewriteUrl($model);
        });

        self::updated(function($model){
            self::rewriteUrl($model);
        });

        self::deleted(function($model){
            $seo_url = SeoUrl::whereTable('pages')->wherePageId($model->id)->first();
            if($seo_url) {
                $seo_url->delete();
            }
        });
    }

    public static function rewriteUrl($model)
    {
        // Get SeoUrl
        $seo_url = SeoUrl::whereTable('pages')->wherePageId($model->id)->first() ?? new SeoUrl(['table' => 'pages', 'page_id' => $model->id]);
        $seo_url->url = $model->url;
        $seo_url->save();
    }

    /**
     * Fetches games by filter type, except standard genre logic
     */
    public function getGamesByFilter($localeId)
    {
        switch ($this->filter_type) {
            case static::FILTER_TYPE_DEVICE_ANDROID:
            case static::FILTER_TYPE_DEVICE_IOS_ANDROID:
                $query =  Game::getGamesDefaultScopeQuery($localeId)->where('games.mobi', 1);
                break;
            case static::FILTER_TYPE_DEVICE_IOS:
                $query =  Game::getGamesDefaultScopeQuery($localeId)->where('games.mobi', 1)->where('games.iphone', 0);
                break;
            case static::FILTER_TYPE_GENRE:
            default:
                throw new \Exception('This filter logic is not implemented in method');
        }
        Game::orderByBest($query);
        return $query;
    }

    public function categories() {
        return $this->hasMany('App\Models\PageGenries', 'id_page', 'id');
    }

    public function heroes() {
        return $this->hasMany('App\Models\PageHeroes', 'id_page', 'id');
    }

    /**
     * @param int|null $lang_id
     * @return object
     */
    public function langsArray( int $lang_id = null) : object
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        $page_lang = PageLang::where('lang_id', $lang_id)->where('page_id', $this->id)->first();

        if (!$page_lang) {
            $page_lang = new PageLang(['lang_id' => $lang_id, 'page_id' => $this->id]);
        }

        return $page_lang;
    }

    /**
     * @param int|null $lang_id
     * @return string
     */
    public function getName( int $lang_id = null) : ?string
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        // Get Name of language
        $page_name = PageLang::where('lang_id', $lang_id)->where('page_id', $this->id)->value('name');

        if (!$page_name) {
            $page_name = PageLang::where('lang_id', Language::getMain()->id)->where('page_id', $this->id)->value('name');
        }

        return $page_name;
    }

    /**
     * @param int|null $lang_id
     * @return string
     */
    public function getMenuName( int $lang_id = null) : ?string
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        // Get Name of language
        $menu_name = PageLang::where('lang_id', $lang_id)->where('page_id', $this->id)->value('menu_name');

        if (!$menu_name) {
            $menu_name = PageLang::where('lang_id', Language::getMain()->id)->where('page_id', $this->id)->value('menu_name');
        }

        return $menu_name;
    }

    public static function getTopMenuDownload(int $current_locale_id) : array
    {
        return Cache::get('top-menu-' . $current_locale_id, function () use ($current_locale_id){
            $pages = self::join('page_langs', 'pages.id', '=', 'page_langs.page_id')
                ->where('page_langs.lang_id', $current_locale_id)
                ->where('pages.public', 1)
                ->where('pages.show_top_menu', 1)
                ->orderBy('pages.sort', 'ASC')
                ->select('pages.id', 'pages.url', 'page_langs.name', 'page_langs.menu_name')
                ->get();
            $out = [];

            foreach ($pages as $page) {
                $out[$page->url] = $page;
            }
            return $out;
        });
    }

    /**
     * @param int $current_locale_id
     * @return object
     */
    public function getCategories(int $current_locale_id) : object
    {
        return Genre::rightJoin('page_genries', 'genres.id', '=', 'page_genries.id_genry')
            ->join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', $current_locale_id)
            ->where('page_genries.id_page', $this->id)
            ->where('genres.public', 1)
            ->orderBy('page_genries.sort', 'ASC')
            ->select('genres.id', 'genres.image', 'genres.url', 'genre_langs.value')
            ->get();
    }

    /**
     * @param int $current_locale_id
     * @return object
     */
    public function getHeroes(int $current_locale_id) : object
    {
        return Heroes::rightJoin('page_heroes', 'heroes.id', '=', 'page_heroes.id_heroy')
            ->join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->where('heroes_langs.langs_id', $current_locale_id)
            ->where('page_heroes.id_page', $this->id)
            ->where('heroes.public', 1)
            ->orderBy('page_heroes.sort', 'ASC')
            ->select('heroes.id', 'heroes.image', 'heroes.url', 'heroes_langs.value')
            ->get();
    }

    public function getBestPages()
    {
        return $this->select(["url"])->where('url', 'like', '%best%')->get()->toArray();
    }

    public function getPageRatingCount()
    {
        return $this->hasMany('App\Models\PagesRating', 'page_id', 'id')->get()->count();
    }

    public function getGenreImageURL(): string
    {
        $path = 'genres/'. $this->id  . '.jpg';

        if (Storage::disk('images')->exists($path)) {
            return Storage::disk('images')->url($path);
        }

        return config('site.genres.image.default');
    }
}