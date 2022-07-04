<?php
/**
 * Created by Artdevue.
 * User: artdevue - Role.php
 * Date: 2019-12-16
 * Time: 07:51
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //protected $table = 'roles';

    protected $fillable = [
        'name', 'alias', 'role_prefix'
    ];

    public function prefix() {
        return $this->hasOne('App\Models\RolePrefix', 'id', 'role_prefix');
    }
}