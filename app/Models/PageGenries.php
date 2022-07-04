<?php
/**
 * Created by Artdevue.
 * User: artdevue - PageGenries.php
 * Date: 2020-02-13
 * Time: 23:49
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PageGenries extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'id_page', 'id_genry', 'sort'
    ];

    public function genre() {
        return $this->hasOne('App\Models\Genre', 'id', 'id_genry');
    }
}