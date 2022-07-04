<?php
/**
 * Created by Artdevue.
 * User: artdevue - GamesUnicUser.php
 * Date: 2020-02-02
 * Time: 01:02
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GamesUnicUser extends Model
{
    protected $fillable = [
        'game_id', 'user_id', 'ip_user', 'count', 'total_time', 'time', 'user_agent', 'name', 'version', 'platform', 'pattern', 'updated_at'
    ];

    /**
     * @return bool
     */
    public function isUserView(): bool
    {
        $ip_user = false;

        // Get User use ID
        if (!Auth::guest()) {
            $ip_user = (bool)self::whereGameId($this->game_id)->whereUserId(Auth::user()->id)->first();
        }

        $ip = request()->ip();

        if (!empty($ip) && !$ip_user) {

            $ip_user = (bool)self::whereGameId($this->game_id)->whereNull('user_id')->whereIpUser($ip)->first();
        }

        return $ip_user;
    }

    /**
     * @param int  $game_id
     * @param null $user_id
     * @return bool|object
     */
    public static function setUser(int $game_id)
    {
        $ip = request()->ip();

        if (!empty($ip)) {

            $ip_user = false;

            // Get User use IP
            if (!Auth::guest()) {
                $ip_user = self::whereGameId($game_id)->whereUserId(Auth::user()->id)->first();
            }

            if (!$ip_user) {
                $ip_user = self::whereGameId($game_id)->whereNull('user_id')->whereIpUser($ip)->first();
                if ($ip_user && !Auth::guest()) {
                    // Update User ID
                    $ip_user->update(['user_id' => Auth::user()->id]);
                }
            }

            if (!$ip_user) {
                $browser = getBrowser();

                $ip_user = new self();
                $ip_user->game_id = $game_id;

                if (!Auth::guest()) {
                    $ip_user->user_id = Auth::user()->id;
                }

                $ip_user->ip_user = $ip;

                foreach ($browser as $key => $item) {
                    $ip_user->$key = $item;
                }

                $ip_user->save();
            }

            // Update Unic users of Game
            Game::find($game_id)->update(['unic_users' => self::whereGameId($game_id)->count()]);

            return $ip_user;
        }

        return false;
    }
}