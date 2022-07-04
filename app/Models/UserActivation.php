<?php
/**
 * Created by Artdevue.
 * User: artdevue - UserActivation.php
 * Date: 2020-02-06
 * Time: 04:54
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserActivation extends Model
{
    /**
     * Indicates model primary keys.
     */
    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $fillable = [
        'user_id', 'token', 'created_at'
    ];

}