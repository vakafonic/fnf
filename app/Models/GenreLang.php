<?php
/**
 * Created by Artdevue.
 * User: artdevue - GenreLang.php
 * Date: 2020-01-02
 * Time: 21:34
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GenreLang extends Model
{
    protected $fillable = [
        'lang_id', 'genre_id', 'value', 'h1', 'description', 'h2', 'description_buttom', 'seo_title', 'seo_description'
    ];

    public function getH1Attribute($value) {
        return !empty($value) ? $value : $this->value;
    }
}