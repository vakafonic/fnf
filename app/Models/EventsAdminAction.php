<?php
/**
 * Created by Artdevue.
 * User: artdevue - EventsAdminAction.php
 * Date: 2019-12-23
 * Time: 12:51
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class EventsAdminAction extends Model
{
    protected $table = 'events_admin_action';

    protected $fillable = [
        'events_admin_id', 'user_id', 'sours_id', 'description', 'created_at'
    ];

    public function admin() {
        return $this->hasOne('App\Models\EventsAdmin', 'id', 'events_admin_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}