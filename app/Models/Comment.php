<?php
/**
 * Created by Artdevue.
 * User: artdevue - Comment.php
 * Date: 2020-02-07
 * Time: 21:49
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $fillable = [
        'user_id', 'ip_user', 'game_id', 'name', 'text', 'confirmed', 'rate_up', 'rate_down', 'created_at'
    ];

    /**
     * Get the user record associated with the comment.
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function rates()
    {
        return $this->hasMany('App\Models\CommentRate','comment_id', 'id');
    }

    /**
     * Get the game that comment.
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * @return bool
     */
    public function isCheckUser() : bool
    {
        $comment = false;

        // Get las comment of User use ID
        if (!Auth::guest()) {
            $comment = $this->user_id == Auth::user()->id;
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip) && !$comment) {

            $comment = $this->ip_user == $ip && is_null($this->user_id);
        }

        return $comment;
    }

    /**
     * @return bool
     */
    public function isChange() : bool
    {
        return $this->isCheckUser() && $this->created_at > date('Y-m-d H:i:s', strtotime('-1 day'));
    }

    /**
     * @return bool
     */
    public function getCheckUserRate()
    {
        $comment_rate = false;

        // Get las comment of User use ID
        if (!Auth::guest()) {
            $comment_rate = CommentRate::whereCommentId($this->id)->whereUserId(Auth::user()->id)->first();;
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip) && !$comment_rate) {

            $comment_rate = CommentRate::whereCommentId($this->id)->whereNull('user_id')->whereUserIp($ip)->first();
        }

        return $comment_rate;
    }

    /**
     * @return bool
     */
    public function isCheckUserRate() : bool
    {
        $comment_rate = false;

        // Get las comment of User use ID
        if (!Auth::guest()) {
            $comment_rate = CommentRate::whereCommentId($this->id)->whereUserId(Auth::user()->id)->exists();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip) && !$comment_rate) {

            $comment_rate = CommentRate::whereCommentId($this->id)->whereNull('user_id')->whereUserIp($ip)->exists();
        }

        return $comment_rate;
    }

    /**
     * @return int|mixed
     */
    public function reorderRate()
    {
        $this->update([
            'rate_up' => $this->rates()->where('rate_check', 1)->count(),
            'rate_down' => $this->rates()->where('rate_check', 0)->count()
            ]);

        return $this->rate_up - $this->rate_down;
    }
}