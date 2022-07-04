<?php
/**
 * Created by Artdevue.
 * User: artdevue - GameCategoriesController.php
 * Date: 2019-12-23
 * Time: 09:06
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;

class GameCategoriesController extends Controller
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
        return view('manager.games.categories.index');
    }

    public function create()
    {
        $title = 'Создать игру';
        return view('manager.games.categories.create');
    }
}