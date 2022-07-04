<?php
/**
 * Created by Artdevue.
 * User: artdevue - Popup.php
 * Date: 2020-02-01
 * Time: 20:52
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'created_by', 'updated_by'
    ];

    /**
     * @param int|null $lang_id
     * @return object
     */
    public function langsArray( int $lang_id = null) : object
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        $page_lang = PopupLang::where('lang_id', $lang_id)->where('popup_id', $this->id)->first();

        if (!$page_lang) {
            $page_lang = new PopupLang(['lang_id' => $lang_id, 'popup_id' => $this->id]);
        }

        return $page_lang;
    }

    /**
     * @param int|null $lang_id
     * @return string
     */
    public function getName( int $lang_id = null) : string
    {
        if (is_null($lang_id)) {
            $lang_id = Language::getMain()->id;
        }

        // Get Name of language
        $page_name = PopupLang::where('lang_id', $lang_id)->where('popup_id', $this->id)->value('name');

        if (!$page_name) {
            $page_name = PopupLang::where('lang_id', Language::getMain()->id)->where('popup_id', $this->id)->value('name');
        }

        return $page_name;
    }
}