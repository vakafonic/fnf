<?php
/**
 * Created by Artdevue.
 * User: artdevue - GamesGenre.php
 * Date: 2020-01-11
 * Time: 13:53
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GamesGenre extends Model
{
    /**
     * Indicates model primary keys.
     */
    protected $primaryKey = ['game_id', 'genry_id'];
    public $incrementing = false;

    protected $fillable = [
        'game_id', 'genry_id','created_by', 'updated_by', 'general'
    ];

    public function genry() {
        return $this->hasOne('App\Models\Genre', 'id', 'genry_id');
    }

    /**
     * @param array $genres
     * @return array
     */
    public static function getIdsOfGenres(array $genres): array
    {
        return self::whereIn('genry_id', $genres)->groupBy('game_id')->pluck('game_id')->toArray();
    }

    /**
     * @param int $game_id
     * @return array
     */
    public static function getIdsOfGame(int $game_id) : array
    {
        return self::whereGameId($game_id)->groupBy('genry_id')->pluck('genry_id')->toArray();
    }

    public static function getGeneralCategoryId(int $gameId)
    {
        $item = self::query()
            ->where('games_genres.game_id', '=', $gameId)
            ->where('games_genres.general', '=' , 1)
            ->first();
        return $item->genry_id ?? null;
    }

    /**
     * @param array $genres
     * @return array
     */
    public static function getIdsOfGenresTopGood(array $genres): array
    {
        $gamesTop = self::join('games', 'games.id', '=', 'games_genres.game_id')->where('games.top', '=', 1)->groupBy('game_id')->pluck('game_id')->toArray();
        $gamesGood = self::join('games', 'games.id', '=', 'games_genres.game_id')->whereIn('genry_id', $genres)->where('games.good', '=', 1)->groupBy('game_id')->pluck('game_id')->toArray();
        $gamesSimilars = GamesSimilar::where('position', '!=', 1)->groupBy('id_game_s')->pluck('id_game_s')->toArray();
        return array_diff(array_merge($gamesTop, $gamesGood), $gamesSimilars);
    }


    public static function getIDsOfGenresTopGoodGames(array $genreIds): array
    {
        return self::join('games', 'games.id', '=', 'games_genres.game_id')
            ->whereIn('games_genres.genry_id', $genreIds)
            ->where(function($query) {
                $query->where('games.top', '=', 1)
                      ->orWhere('games.good', '=', 1);
            })
            ->groupBy('game_id')
            ->pluck('game_id')
            ->toArray();
    }

}