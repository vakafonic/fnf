<?php
/**
 * Created by Artdevue.
 * User: artdevue - PageLang.php
 * Date: 2020-01-23
 * Time: 00:33
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PageLang extends Model
{
    protected $fillable = [
        'page_id', 'lang_id', 'name', 'h2', 'menu_name', 'description_top', 'description_buttom', 'seo_name', 'seo_description'
    ];

}