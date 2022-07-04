<?php
/**
 * Created by Artdevue.
 * User: artdevue - TranslationLang.php
 * Date: 2019-12-23
 * Time: 12:36
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TranslationLang extends Model
{
    //protected $table = 'translation_lang';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'translation_id', 'language_id', 'name'
    ];

    public function lang() {
        return $this->hasOne('App\Models\Language', 'id', 'language_id');
    }
}