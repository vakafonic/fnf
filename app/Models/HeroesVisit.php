<?php
/**
 * Created by Artdevue.
 * User: artdevue - HeroesVisit.php
 * Date: 2020-01-23
 * Time: 12:32
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HeroesVisit extends Model
{
    /**
     * Indicates model primary keys.
     */
    protected $primaryKey = 'heroy_id';

    public $incrementing = false;

    protected $fillable = [
        'heroy_id', 'visit'
    ];
}