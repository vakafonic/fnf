<?php
/**
 * Created by Artdevue.
 * User: artdevue - GameFeaturesController.php
 * Date: 2019-12-23
 * Time: 10:35
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


class GameFeaturesController extends Controller
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        parent::__construct();
    }

    public function index()
    {
        return view('manager.game_features.index');
    }

    public function create()
    {
        return view('manager.game_features.create');
    }
}