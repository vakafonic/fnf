<?php
/**
 * Created by Artdevue.
 * User: artdevue - CronController.php
 * Date: 2020-02-15
 * Time: 01:42
 * Project: gamesgo.club
 */

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GamesGenre;
use App\Models\GamesLike;
use App\Models\GamesUnicUser;
use App\Models\Genre;
use App\Models\GenreBestGame;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class CronController extends BaseController
{
    public function best()
    {
        // clear table
        GenreBestGame::truncate();

        $games_genries = GamesGenre::groupBy('genry_id')->get();

        foreach ($games_genries as $genry) {
            // Get all games for Genry
            $games_genry = GamesGenre::whereGenryId($genry->genry_id)->pluck('game_id');

            // get procent
            $total_best_games = (int)(count($games_genry) * (int)$genry->genry->best_games / 100);

            $games = GamesUnicUser::join('games', 'games.id', '=', 'games_unic_users.game_id')
                ->whereIn('games_unic_users.game_id', $games_genry)
                ->groupBy('games_unic_users.game_id')
                ->orderBy('games_unic_users.time', 'ASC')
                ->orderBy('games.rating', 'ASC')
                ->havingRaw('SUM(games_unic_users.count) > ?', [9])
                ->take($total_best_games)
                ->pluck('games_unic_users.game_id');

            $insert = [];
            $genry_id = $genry->genry_id;
            foreach ($games as $game_id) {
                // Get Game
                $game = Game::find($game_id);
                if ($game->rating > 0) {
                    $game->update([
                        'best_game' => 1
                    ]);
                }
                array_push($insert, compact('genry_id', 'game_id'));
            }

            if (!empty($insert)) {
                GenreBestGame::insert($insert);
            }
        }

        die('end update best');
    }

    /**
     * Game rating
     */
    public function rating()
    {
        /*// Total votes
        $total_votes = GamesLike::count();

        // Average rating of all votes
        $average_total_rating = GamesLike::avg('game_like');

        $games = GamesLike::groupBy('game_id')
            ->select([DB::raw("count(*) as total_like"), DB::raw("AVG(game_like) as avg_like"), 'game_id'])
            ->get();

        foreach ($games as $game)
        {
            $upgame = Game::find($game->game_id);
            if($upgame) {
                //$upgame->update(['rating' => (int)((int)$game->sum_like/$game->total_like*100)]);
                $upgame->update(['rating' => (int)($game->total_like/($game->total_like+$total_votes) * $game->avg_like + $total_votes/($game->total_like+$total_votes) * $average_total_rating * 100)]);
            }
        }*/
        // Total votes
        $total_votes = GamesLike::count();
        // Total Rating
        $total_rating = GamesLike::sum('game_like');

        $games = GamesLike::groupBy('game_id')
            ->select([DB::raw("count(*) as total_like"), DB::raw("SUM(game_like) as sum_like"), 'game_id'])
            ->get();

        foreach ($games as $game) {
            $upgame = Game::find($game->game_id);
            if ($upgame) {
                $upgame->update(['rating' => (int)(($total_rating + $game->sum_like) / ($total_votes + $game->total_like) * 100)]);
            }
        }

        die('end update rating');
    }

    public function ratingPage()
    {
        $url_array = ['App\Models\GenresRating', 'App\Models\HeroesRating', 'App\Models\PagesRating'];

        foreach ($url_array as $tableModel)

        // get All votes
        $all_vote = $tableModel::select([DB::raw("count(*) as total_like"), DB::raw("SUM(rating) as sum_like")])
            ->first();

        $games = $tableModel::groupBy('page_id')->select([DB::raw("count(*) as total_like"), DB::raw("SUM(rating) as sum_like"), 'page_id'])->get();

        foreach ($games as $game_vote) {
            $rating = round(($all_vote->sum_like + $game_vote->sum_like) / ($all_vote->total_like + $game_vote->total_like), 2);
            if ($page = $tableModel::wherePageId($game_vote->page_id)->first()) {
                $page->page->rating = $rating;
                $page->page->save();
            }
        }

        die('end update rating page');
    }
}