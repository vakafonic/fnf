<?php
/**
 * Created by Artdevue.
 * User: artdevue - GamesLike.php
 * Date: 2020-02-02
 * Time: 12:44
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GamesLike extends Model
{
    protected $fillable = [
        'game_id', 'user_id', 'ip_user'
    ];
}