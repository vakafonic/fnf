<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';

    protected $fillable = [
        'user_id', 'role_id'
    ];

    public function role() {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }
}
