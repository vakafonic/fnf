<?php
/**
 * Created by Artdevue.
 * User: artdevue - TranslationController.php
 * Date: 2019-12-26
 * Time: 18:52
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;

use App\Models\Language;
use App\Models\Translation;
use App\Models\TranslationLang;
use Illuminate\Http\Request, Validator;

class TranslationController extends Controller
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
     * View page for tranlation of languages
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function translation()
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        return view('manager.setting.translation');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function translationAll(Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $rows = [];

        // Get all criteria
        $order = $request->input('order', 'asc');
        $sort = $request->input('sort', 'id');

        $translangs = Translation::orderBy($sort, $order)->get();
        foreach ($translangs as $translang) {

            $trans_array = [];
            foreach ($translang->langs as $lang) {
                $trans_array[] = $lang->lang->code . ': ' . $lang->name;
            }

            $rows[] = [
                'id'       => $translang->id,
                'key_lang' => $translang->key_lang,
                'trans'    => implode('<br> ', $trans_array)
            ];
        }

        return response()->json($rows, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function apiGetNew()
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $message = 'Запрос выполнен';
        $action = 'success';

        // Get langs
        $trans = new Translation();
        $langs = $this->_langsArray();

        $html = view('manager.api.trans.get', compact('trans', 'langs'))->render();

        return response()->json(compact('action', 'message', 'html'), 200);
    }

    /**
     * @param Translation $trans
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Throwable
     */
    public function apiGettrans(Translation $trans)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $message = 'Запрос выполнен';
        $action = 'success';

        // Get langs
        $langs = $this->_langsArray($trans->id);

        $html = view('manager.api.trans.get', compact('trans', 'langs'))->render();

        return response()->json(compact('action', 'message', 'html'), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function apiGetSave(Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $input = $request->except('_method', '_token');
        $trans = !empty($input['id']) ? Translation::find($input['id']) : new Translation();

        $html = '';
        $action = 'danger';
        $message = 'Ошибка записи';

        $validator = Validator::make($input, [
            'key_lang'                => 'required|alpha_dash|max:255|unique:translations' . (!empty($input['id']) ? ',id,' . $input['id'] : ''),
            Language::getMain()->code => 'required|max:255',
        ]);

        if ($validator->fails() && !isset($input['editingModal'])) {
            $langs = $this->_langsArray($trans->id);
            $html = view('manager.api.trans.get', compact('trans', 'langs'))->withErrors($validator)->render();
        } else {

            $message_f = 'создали новую';
            if (!empty($input['id'])) {
                $message_f = 'обновили';
                $trans->updated_by = $this->user->id;
            } else {
                $trans->key_lang = !empty($input['key_lang']) ? $input['key_lang'] : $input[Language::getMain()->code];
                $trans->created_by = $this->user->id;
            }

            $trans->save();

            // Create or update translation
            foreach (Language::all() as $lang) {
                if (!empty($input[$lang->code])) {
                    TranslationLang::updateOrCreate(
                        ['translation_id' => $trans->id, 'language_id' => $lang->id],
                        ['name' => $input[$lang->code]]
                    );
                }
            }

            $action = 'success';
            $message = 'Вы ' . $message_f . ' перевод';
        }

        return response()->json(compact('action', 'message', 'html'), 200);
    }

    /**
     * @param null $trans_id int
     * @return array
     */
    private function _langsArray($trans_id = null)
    {
        $langs_obj = Language::all();
        $langs = [];
        foreach ($langs_obj as $lang) {
            $value = '';

            if (!empty($trans_id)) {
                // Get trans
                $value = TranslationLang::where('translation_id', $trans_id)->where('language_id', $lang->id)->value('name');
            }

            $langs[$lang->code] = [
                'name'  => $lang->name,
                'value' => $value,
                'main'  => $lang->main
            ];
        }

        return $langs;
    }
}