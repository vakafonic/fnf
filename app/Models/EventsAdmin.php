<?php
/**
 * Created by Artdevue.
 * User: artdevue - EventsAdmin.php
 * Date: 2019-12-23
 * Time: 12:49
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class EventsAdmin extends Model
{
    protected $table = 'events_admin';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'name', 'key_ev'
    ];
}