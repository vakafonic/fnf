<?php
/**
 * Created by Artdevue.
 * User: artdevue - RatingController.php
 * Date: 2020-03-24
 * Time: 10:48
 * Project: gamesgo.club
 */

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\GenresRating;
use App\Models\HeroesRating;
use App\Models\Page;
use App\Models\PagesRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        parent::__construct();
    }

    public function postAdd(Request $request)
    {
        $id = (int)$request->input('id');
        $url = (string)$request->input('url');
        $rating = (int)$request->input('rating');
        $success = false;
        $message = $this->lang['invalid_request'];

        try {

            if (!empty($id) && !empty($url) && ($rating > 0 || $rating < 6)) {

                if (self::isCheckUserRate($url, $id)) {
                    throw new \Exception($this->lang['you_have_already_voted']);
                }

                $tableModel = self::swithTableFromUrl($url);
                $page_rating = new $tableModel();
                $page_rating->page_id = $id;
                $page_rating->user_id = $this->user ? $this->user->id : null;
                $page_rating->ip_user = $request->ip();
                $page_rating->rating = $rating;
                $page_rating->save();

                $message = $this->lang['your_vote_accepted'];
                $success = true;

                self::reorderRating($url, $id);
            }

        } catch (\Exception $e) {
            $message = $this->lang['you_have_already_voted'];
        }

        return response()->json(compact('message', 'success', 'rating'), 200);
    }

    /**
     * @param string $url
     * @param int    $id
     * @return bool
     */
    public static function isCheckUserRate(string $url, int $id): bool
    {
        $comment_rate = false;

        $tableModel = self::swithTableFromUrl($url);

        // Get las comment of User use ID
        if (!Auth::guest()) {
            $comment_rate = $tableModel::wherePageId($id)->whereUserId(Auth::user()->id)->exists();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip) && !$comment_rate) {

            $comment_rate = $tableModel::wherePageId($id)->whereNull('user_id')->whereIpUser($ip)->exists();
        }

        return $comment_rate;
    }

    public static function getUserRating(string $url, int $id)
    {
        $comment_rate = [];

        $tableModel = self::swithTableFromUrl($url);

        // Get las comment of User use ID
        if (!Auth::guest()) {
            $comment_rate = $tableModel::wherePageId($id)->whereUserId(Auth::user()->id)->select('rating')->get()->toArray();
        }

        $ip = request()->ip();

        // Get las comment of User use IP
        if (!empty($ip) && !$comment_rate) {

            $comment_rate = $tableModel::wherePageId($id)->whereNull('user_id')->whereIpUser($ip)->select('rating')->get()->toArray();
        }
        if(count($comment_rate) > 0){
            $comment_rate = reset($comment_rate)['rating'];
        } else {
            $comment_rate = 0;
        }
        return $comment_rate;
    }

    /**
     * @param string $url
     * @param int    $id
     */
    public static function reorderRating(string $url, int $id)
    {
        $tableModel = self::swithTableFromUrl($url);
        // get All votes
        $all_vote = $tableModel::select([DB::raw("count(*) as total_like"), DB::raw("SUM(rating) as sum_like")])
            ->first();

        $game_vote = $tableModel::wherePageId($id)->select([DB::raw("count(*) as total_like"), DB::raw("SUM(rating) as sum_like")])
            ->first();

        $rating = round(($all_vote->sum_like + $game_vote->sum_like) / ($all_vote->total_like + $game_vote->total_like), 2);

        if ($page = $tableModel::wherePageId($id)->first()) {
            $page->page->rating = $rating;
            $page->page->save();
        }
    }

    /**
     * @param string $url
     * @return string
     */
    private static function swithTableFromUrl(string $url): string
    {
        switch ($url) {
            case 'genre':
                $tableModel = 'App\Models\GenresRating';
                break;
            case 'hero';
                $tableModel = 'App\Models\HeroesRating';
                break;
            default:
                $tableModel = 'App\Models\PagesRating';
        }

        return $tableModel;
    }
}