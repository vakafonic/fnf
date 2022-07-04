<?php
/**
 * Created by Artdevue.
 * User: artdevue - CommandController.php
 * Date: 2020-01-07
 * Time: 13:28
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\ButtonsPlay;
use App\Models\ButtonsPlayLang;
use App\Models\Language;
use Illuminate\Http\Request, Validator;

class CommandController extends Controller
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
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        return view('manager.command.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $rows = [];

        if ($this->user->role != 1) {
            return response()->json($rows, 200);
        }

        // Get all criteria
        $order = $request->input('order', 'asc');
        //$sort = $request->input('sort9', 'id');

        $comands = ButtonsPlay::orderBy('id', $order)->get();
        foreach ($comands as $comand) {
            $trans_array = [];
            foreach ($comand->langs as $lang) {
                $trans_array[] = $lang->lang->code . ': ' . $lang->name;
            }

            $rows[] = [
                'id'    => $comand->id,
                'icon'  => $comand->icon,
                'name'  => $comand->getName(1),
                'trans' => '<mark>' . implode('</mark>, <mark>', $trans_array) . '</mark>',
            ];
        }

        return response()->json($rows, 200);
    }

    /**
     * @param ButtonsPlay $buttonsPlay
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function postEdit(ButtonsPlay $buttonsPlay)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $html = view('manager.command.edit', compact('buttonsPlay'))->render();

        $action = 'success';

        return response()->json(compact('html', 'action'), 200);
    }

    /**
     * @param ButtonsPlay $buttonsPlay
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Throwable
     */
    public function postSave(ButtonsPlay $buttonsPlay, Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $input = $request->except('_method', '_token');
        $html = '';
        $action = 'danger';
        $message = 'Ошибка записи';

        /*$validator = Validator::make($input, [
            Language::getMain()->code => 'required|max:255',
        ]);*/

        /*if ($validator->fails()) {
            $html = view('manager.command.edit', compact('buttonsPlay'))->withErrors($validator)->render();
        } else {*/

            $buttonsPlay->update(['updated_by' => $this->user->id]);

            // Create or update translation
            foreach (Language::all() as $lang) {
                if (!empty($input[$lang->code])) {
                    ButtonsPlayLang::updateOrCreate(
                        ['buttons_play_id' => $buttonsPlay->id, 'lang_id' => $lang->id],
                        ['name' => $input[$lang->code]]
                    );
                }
            }

            $action = 'success';
            $message = 'Вы обновили команду';
        //}

        return response()->json(compact('action', 'message', 'html'), 200);
    }
}