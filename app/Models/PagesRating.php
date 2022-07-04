<?php
/**
 * Created by Artdevue.
 * User: artdevue - PagesRating.php
 * Date: 2020-03-23
 * Time: 00:12
 * Project: gamesgo.club
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagesRating extends Model
{
    protected $fillable = [
        'id', 'page_id', 'user_id', 'ip_user', 'rating', 'created_at', 'updated_at'
    ];

    public function page()
    {
        return $this->hasOne('App\Models\Page', 'id', 'page_id');
    }
}