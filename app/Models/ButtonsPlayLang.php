<?php
/**
 * Created by Artdevue.
 * User: artdevue - ButtonsPlayLang.php
 * Date: 2020-01-11
 * Time: 14:07
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ButtonsPlayLang extends Model
{
    /**
     * Indicates model primary keys.
     */
    protected $primaryKey = ['lang_id', 'buttons_play_id'];

    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'lang_id', 'buttons_play_id', 'name'
    ];

    public function lang() {
        return $this->hasOne('App\Models\Language', 'id', 'lang_id');
    }
}