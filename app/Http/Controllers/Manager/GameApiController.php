<?php
/**
 * Created by Artdevue.
 * User: artdevue - GameApiController.php
 * Date: 2020-01-05
 * Time: 14:03
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Developer;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        parent::__construct();
    }

    public function getDeveloper(Request $request)
    {
        $action = 'success';
        $q = $request->get('q', null);
        if (!empty($q))
        {
            $developers = Developer::where('name', 'LIKE', "%{$q}%")->get();
        } else {
            $developers = Developer::all();
        }

        return response()->json($developers, 200);
    }
}