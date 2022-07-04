<?php
/**
 * Created by Artdevue.
 * User: artdevue - SettingController.php
 * Date: 2019-12-23
 * Time: 13:27
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Language;
use Illuminate\Http\Request, Validator;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        parent::__construct();
    }

    public function language()
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        return view('manager.setting.language');
    }

    /**
     * Post & Ajax request of update or create new language item
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function languagePost(Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        if (!$request->ajax()) {
            return redirect()->route('m.setting.language')->with('error', 'Должен быть ajax запрос. Если ошибка повторяется обратитесь к администратору');
        }

        $action = 'danger';
        $message = 'Ошибка записи';

        $validator = Validator::make($request->except('_method', '_token'), [
            'name'   => 'required|max:100',
            'code'   => 'required|max:2',
            'locale' => 'required|max:100',
        ]);

        $update_req = $request->only('name', 'locale', 'sort', 'code');
        $update_req['status'] = $request->input('status', 0);
        $update_req['main'] = $request->input('main', 0);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
        } else {
            // Update or Create record

            $lang = Language::where('code', $update_req['code'])->first();

            if ($lang) {
                $update_req['updated_by'] = $this->user->id;
                $lang->update($update_req);
            } else {
                $update_req['created_by'] = $this->user->id;
                $lang = new Language($update_req);
                $lang->save();
            }
            /*$lang = Language::firstOrNew(
                ['code' => $request->input('code')],
                [
                    $update_req
                ]
            );*/

            // If this "main" then update all items to the 0
            if ($lang->main == 1) {
                Language::where('main', 1)->where('id', '!=', $lang->id)->update(['main' => 0]);
            }

            $action = 'success';
            $message = 'Сохранение записи';
        }

        return response()->json(compact('action', 'message'), 200);
    }

    /**
     * Delete Language
     *
     * @param Language $language
     * @param Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|string
     * @throws \Exception
     */
    public function languageDelete(Language $language, Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        if (!$request->ajax()) {
            return redirect()->route('m.setting.language')->with('error', 'Должен быть ajax запрос. Если ошибка повторяется обратитесь к администратору');
        }

        $action = 'danger';
        $message = 'Ошибка удаления';

        if ($language->delete()) {
            $action = 'success';
            $message = 'Вы удачно удалил язык';
        }

        return response()->json(compact('action', 'message'), 200);
    }

    /**
     * Get all Languages for table
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function languageAll(Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $rows = [];

        // Get all criteria
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $order = $request->input('order', 'asc');
        $sort = $request->input('sort', 'id');
        $status = $request->input('status', 0);
        $search = $request->input('search', null);

        $languages = Language::orderBy($sort, $order)->skip($offset)->take($limit)->get();
        foreach ($languages as $language) {
            $rows[] = $language->toArray();
        }

        return response()->json($rows, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function languageSort(Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $data = $request->input('data', []);
        foreach ($data as $sort => $id)
        {
            Language::whereId((int)$id)->update(compact('sort'));
        }

        return response()->json('success', 200);
    }

    /**
     * @param Language $language
     * @return \Illuminate\Http\JsonResponse
     */
    public function languageGet(Language $language)
    {
        return response()->json($language, 200);
    }
}