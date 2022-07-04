<?php
/**
 * Created by Artdevue.
 * User: artdevue - GenresRating.php
 * Date: 2020-03-23
 * Time: 00:03
 * Project: gamesgo.club
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenresRating extends Model
{
    protected $fillable = [
        'id', 'page_id', 'user_id', 'ip_user', 'rating', 'created_at', 'updated_at'
    ];

    public function page()
    {
        return $this->hasOne('App\Models\Genre', 'id', 'page_id');
    }
}