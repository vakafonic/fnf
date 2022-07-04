<?php
/**
 * Created by Artdevue.
 * User: artdevue - Translation.php
 * Date: 2019-12-23
 * Time: 12:34
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    //protected $table = 'translations';

    protected $fillable = [
        'key_lang', 'default_lang'
    ];

    public function langs() {
        return $this->hasMany('App\Models\TranslationLang', 'translation_id', 'id');
    }
}