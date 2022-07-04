<?php
/**
 * Created by Artdevue.
 * User: artdevue - HeroesRating.php
 * Date: 2020-03-23
 * Time: 00:11
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HeroesRating extends Model
{
    protected $fillable = [
        'id', 'page_id', 'user_id', 'ip_user', 'rating', 'created_at', 'updated_at'
    ];

    public function page()
    {
        return $this->hasOne('App\Models\Heroes', 'id', 'page_id');
    }
}