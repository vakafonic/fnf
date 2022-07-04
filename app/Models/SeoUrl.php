<?php
/**
 * Created by Artdevue.
 * User: artdevue - SeoUrl.php
 * Date: 2020-03-25
 * Time: 00:50
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SeoUrl extends Model
{
    protected $table = 'seo_url';

    /**
     * Indicates model primary keys.
     */
    protected $primaryKey = 'url';

    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'table', 'page_id'
    ];
}