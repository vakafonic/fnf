<?php
/**
 * Created by Artdevue.
 * User: artdevue - GamesLang.php
 * Date: 2020-01-11
 * Time: 13:23
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GamesLang extends Model
{
    //protected $table = 'games_langs';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'games_id', 'lang_id', 'name', 'sub_name', 'description', 'how_play', 'seo_name', 'seo_description',
        'size_width', 'height', 'width', 'iframe_url', 'url_site', 'link_game', 'mobi', 'target_blank', 'no_block_ad',
        'sandbox'
    ];
}