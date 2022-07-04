<?php
/**
 * Created by Artdevue.
 * User: artdevue - Seopage.php
 * Date: 2020-02-23
 * Time: 01:26
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Seopage extends Model
{
    protected $fillable = [
        'url', 'comment', 'public', 'sort', 'created_by', 'updated_by', 'public'
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
            $seo_url = SeoUrl::whereTable('seopages')->wherePageId($model->id)->first();
            if($seo_url) {
                $seo_url->delete();
            }
        });
    }

    public static function rewriteUrl($model)
    {
        // Get SeoUrl
        $seo_url = SeoUrl::whereTable('seopages')->wherePageId($model->id)->first() ?? new SeoUrl(['table' => 'seopages', 'page_id' => $model->id]);
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
        $seopage_name = SeopagesLang::where('lang_id', $lang_id)->where('seopage_id', $this->id)->value('value');

        if (!$seopage_name) {
            $seopage_name = SeopagesLang::where('lang_id', Language::getMain()->id)->where('seopage_id', $this->id)->value('value');
        }

        return $seopage_name;
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

        $seopage_lang = SeopagesLang::where('lang_id', $lang_id)->where('seopage_id', $this->id)->first();

        if (!$seopage_lang) {
            $seopage_lang = new SeopagesLang(['lang_id' => $lang_id, 'seopage_id' => $this->id]);
        }

        return $seopage_lang;
    }

    /**
     * @param int $current_locale_id
     * @return object
     */
    public function getGenries(int $current_locale_id) : object
    {
        return Genre::rightJoin('seopages_genres', 'genres.id', '=', 'seopages_genres.genry_id')
            ->join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', $current_locale_id)
            ->where('seopages_genres.seopages_id', $this->id)
            ->where('genres.public', 1)
            ->orderBy('seopages_genres.sort', 'ASC')
            ->select('genres.id', 'genres.image', 'genres.url', 'genre_langs.value')
            ->get();
    }

    /**
     * @param int $current_locale_id
     * @return object
     */
    public function getHeroes(int $current_locale_id) : object
    {
        return Heroes::rightJoin('seopages_heroes', 'heroes.id', '=', 'seopages_heroes.heroes_id')
            ->join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->where('heroes_langs.langs_id', $current_locale_id)
            ->where('seopages_heroes.seopages_id', $this->id)
            ->where('heroes.public', 1)
            ->orderBy('seopages_heroes.sort', 'ASC')
            ->select('heroes.id', 'heroes.image', 'heroes.url', 'heroes_langs.value')
            ->get();
    }
}