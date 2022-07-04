<?php
/**
 * Created by Artdevue.
 * User: artdevue - PasswordReset.php
 * Date: 2020-02-05
 * Time: 19:38
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    /**
     * Indicates model primary keys.
     */
    protected $primaryKey = 'email';

    public $incrementing = false;

    protected $fillable = [
        'email', 'token', 'created_at'
    ];
}