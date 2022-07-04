<?php
/**
 * Created by Artdevue.
 * User: artdevue - CommentRate.php
 * Date: 2020-02-09
 * Time: 03:22
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CommentRate extends Model
{
    protected $fillable = [
        'comment_id', 'user_id', 'user_ip', 'rate_check', 'created_at'
    ];

}