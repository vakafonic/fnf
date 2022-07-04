<?php
/**
 * Created by Artdevue.
 * User: artdevue - HeroesLang.php
 * Date: 2020-01-04
 * Time: 14:28
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HeroesLang extends Model
{
    protected $fillable = [
        'langs_id', 'heroes_id', 'value', 'h1', 'description', 'h2', 'description_buttom', 'seo_title', 'seo_description'
    ];

    public function lang() {
        return $this->hasOne('App\Models\Language', 'id', 'langs_id');
    }

    public function getH1Attribute($value) {
        return !empty($value) ? $value : $this->value;
    }
}