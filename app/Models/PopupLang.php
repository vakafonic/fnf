<?php
/**
 * Created by Artdevue.
 * User: artdevue - PopupLang.php
 * Date: 2020-02-01
 * Time: 20:54
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PopupLang extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'popup_id', 'lang_id', 'name', 'text'
    ];
}