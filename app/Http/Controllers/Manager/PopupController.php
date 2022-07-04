<?php
/**
 * Created by Artdevue.
 * User: artdevue - PopupController.php
 * Date: 2020-02-01
 * Time: 20:56
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Language;
use App\Models\Popup;
use Illuminate\Http\Request, Validator;

class PopupController extends Controller
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        parent::__construct();
    }

    /**
     * View all pages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function index()
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        return view('manager.popups.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $rows = [];

        $pages = Popup::all();

        foreach ($pages as $page) {
            $rows[] = [
                'id'   => $page->id,
                'name' => $page->getName()
            ];
        }

        return response()->json($rows, 200);
    }

    /**
     * @param Popup $popup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function getEdit(Popup $popup)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        // Get Langs
        $langs = [];
        foreach (Language::all() as $lang) {
            $langs[$lang->code] = $popup->langsArray($lang->id);
        }
        return view('manager.popups.edit', compact('popup', 'langs'));
    }

    /**
     * @param Popup   $popup
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function putEdit(Popup $popup, Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $input_all = $request->except('_method', '_token');
        $default_lang_code = Language::getMain('code');

        $validator_array = [];

        foreach (Language::all() as $lang) {
            $validator_array['name_' . $lang->code] = ['max:255', $default_lang_code == $lang->code ? 'required' : null];
        }

        $validator = Validator::make($input_all, $validator_array);

        if ($validator->fails()) {
            return redirect($request->url())->withInput()->withErrors($validator);
        }

        try {
            $popup->updated_by = $this->user->id;
            $popup->save();

            $array_filds = ['name', 'text'];

            foreach (Language::all() as $lang) {
                $language = $popup->langsArray($lang->id);
                foreach ($array_filds as $fild) {
                    $language->$fild = $input_all[$fild . '_' . $lang->code];
                }

                $language->save();
            }
        } catch (\Exception $e) {
            // do task when error
            return redirect($request->url())->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('m.popups.index')->with('success', 'Вы щиновили PopUp окно');
    }
}