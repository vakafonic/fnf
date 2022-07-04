<?php
/**
 * Created by Artdevue.
 * User: artdevue - RolePrefix.php
 * Date: 2019-12-16
 * Time: 07:55
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RolePrefix extends Model
{
    protected $table = "role_prefix";

    protected $fillable = [
        'name', 'prefix'
    ];

    public function roles() {
        return $this->hasMany('App\Models\Role', 'role_prefix', 'id');
    }
}