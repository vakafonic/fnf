<?php
/**
 * Created by Artdevue.
 * User: artdevue - GamesHeroe.php
 * Date: 2020-01-11
 * Time: 13:54
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GamesHeroe extends Model
{
    /**
     * Indicates model primary keys.
     */
    protected $primaryKey = ['game_id', 'heroy_id'];
    public $incrementing = false;

    protected $fillable = [
        'game_id', 'heroy_id', 'created_by', 'updated_by'
    ];

    /**
     * @param array $hero
     * @return array
     */
    public static function getIdsOfhero(array $hero): array
    {
        return self::whereIn('heroy_id', $hero)->groupBy('game_id')->pluck('game_id')->toArray();
    }

    public static function getHeroesGamesTopGood(array $gamesIds): array
    {
        $heroesIds = self::whereIn('game_id', $gamesIds)
            ->groupBy('heroy_id')
            ->pluck('heroy_id')
            ->toArray();

        return self::join('games', 'games.id', '=', 'games_heroes.game_id')
            ->whereIn('heroy_id', $heroesIds)
            ->where(function($query) {
                $query->where('games.top', '=', 1)
                      ->orWhere('games.good', '=', 1);
            })
            ->groupBy('game_id')
            ->pluck('game_id')
            ->toArray();
    }
}