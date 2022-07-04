<?php
/**
 * Created by Artdevue.
 * User: artdevue - GenresVisit.php
 * Date: 2020-01-23
 * Time: 12:28
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GenresVisit extends Model
{
    /**
     * Indicates model primary keys.
     */
    protected $primaryKey = 'genre_id';

    public $incrementing = false;

    protected $fillable = [
        'genre_id', 'visit'
    ];
}