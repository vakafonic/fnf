<?php
/**
 * Created by Artdevue.
 * User: artdevue - ButtonsPlay.php
 * Date: 2020-01-11
 * Time: 14:04
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ButtonsPlay extends Model
{
    protected $fillable = [
        'icon', 'created_by', 'updated_by'
    ];

    public function langs()
    {
        return $this->hasMany('App\Models\ButtonsPlayLang', 'buttons_play_id', 'id');
    }

    /**
     * @param null $lang_id
     * @return ButtonsPlayLang|Model|null
     */
    public function langObject($lang_id = null)
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        $b_play = ButtonsPlayLang::where('lang_id', $lang_id)->where('buttons_play_id', $this->id)->first();

        if (!$b_play) {
            $b_play = new ButtonsPlayLang(['lang_id' => $lang_id, 'buttons_play_id' => $this->id]);
        }

        return $b_play;
    }

    /**
     * @param null $lang_id
     * @return mixed
     */
    public function getName($lang_id = null)
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        // Get Name of language
        $heroes_name = $this->langObject($lang_id)->name;

        if (strlen($heroes_name) > 0) {
            return $heroes_name;
        }

        return $this->langObject(Language::getMain())->name;
    }
}