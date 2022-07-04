<?php
/**
 * Created by Artdevue.
 * User: artdevue - HomeController.php
 * Date: 2019-11-24
 * Time: 16:42
 * Project: gamesgo.club
 */


namespace App\Http\Controllers\Manager;

use App\Models\Search;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth.admin');
        parent::__construct();
        Carbon::setLocale('ru');
        setlocale(LC_TIME, 'ru');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manager.home');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchWord()
    {
        $this->middleware('auth.admin');

        return view('manager.searchword');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchWord(Request $request)
    {
        $this->middleware('auth.admin');

        // Get all criteria
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $order = $request->input('order', 'desc');
        $sort = $request->input('sort', 'count');
        $search = $request->input('search', null);
        $rows = [];

        $query = Search::query();
        if (!empty($search)) {
            $query->where('word', 'LIKE', "%$search%");
        }

        $total = $query->count();

        $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit);
        $words = $query->get();

        foreach ($words as $word) {
            $date = $word->updated_at ?? $word->created_at;
            $rows[] = [
                'word'       => $word->word,
                'count'      => $word->count,
                'updated_at' => $date,
                'format_ud'  => $date->formatLocalized('%d %B %Y в %H:%M')
            ];
        }

        return response()->json(compact('rows', 'total'), 200);
    }
}
