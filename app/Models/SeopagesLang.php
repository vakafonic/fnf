<?php
/**
 * Created by Artdevue.
 * User: artdevue - SeopagesLang.php
 * Date: 2020-02-23
 * Time: 01:29
 * Project: gamesgo.club
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeopagesLang extends Model
{
    protected $fillable = [
        'lang_id', 'seopage_id', 'value', 'description', 'h2', 'description_buttom', 'seo_title', 'seo_description'
    ];
}