<?php
/**
 * Created by Artdevue.
 * User: artdevue - Developersontroller.php
 * Date: 2020-01-05
 * Time: 01:38
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Developer;
use App\Models\EventsAdminAction;
use App\Models\Language;
use Illuminate\Http\Request, Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class DevelopersController extends Controller
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
        if (!$this->user->isRoleAction('devel_view')) {
            return $this->get403Code();
        }

        return view('manager.developers.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        $rows = [];

        if (!$this->user->isRoleAction('devel_view')) {
            return response()->json($rows, 200);
        }

        // Get all criteria
        $order = $request->input('order', 'asc');
        $sort = $request->input('sort', 'name');
        $status = $request->input('status', 0);
        $search = $request->input('search', null);

        $developers = Developer::orderBy($sort, $order)->get();
        foreach ($developers as $developer) {

            $rows[] = $developer->toArray();
        }

        return response()->json($rows, 200);
    }

    /**
     * @param Developer $developer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function postNew(Developer $developer)
    {
        $message = 'Запрос выполнен';
        $action = 'success';

        $html = view('manager.developers.new', compact('developer'))->render();

        return response()->json(compact('action', 'message', 'html'), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function postSave(Request $request)
    {
        $input = $request->except('_method', '_token');
        $developer = !empty($input['id']) ? Developer::find((int)$input['id']) : new Developer();

        $html = '';
        $action = 'danger';
        $message = 'Ошибка записи';

        $input['url'] = !empty($input['url']) ? $input['url'] : Str::slug($input['name']);

        $validator = Validator::make($input, [
            'url'  => [
                'required',
                'alpha_dash',
                'max:255',
                !empty($developer->id) ? Rule::unique('developers')->ignore($developer->id) : null,
            ],
            'name' => [
                'required',
                'max:255',
                !empty($developer->id) ? Rule::unique('developers')->ignore($developer->id) : null,
            ]
            //'url'  => 'required|alpha_dash|max:255|unique:developers' . (!empty($input['id']) ? ',id,' . $input['id'] : ''),
            //'name' => 'required|max:255|unique:developers' . (!empty($input['id']) ? ',id,' . $input['id'] : '')
        ]);

        if ($validator->fails()) {
            $html = view('manager.developers.new', compact('developer'))->withErrors($validator)->render();
        } else {
            $message_f = 'создали нового';
            $events_admin_id = 5;
            if (!empty($input['id'])) {
                $message_f = 'обновили';
                $developer->updated_by = $this->user->id;
                $events_admin_id = 4;
            } else {
                $developer->created_by = $this->user->id;
            }

            $developer->url = $input['url'];
            $developer->name = $input['name'];
            $developer->save();

            $action = 'success';
            $message = 'Вы ' . $message_f . ' разработчика';

            // Set events of create user
            EventsAdminAction::create(['events_admin_id' => $events_admin_id, 'user_id' => $this->user->id, 'description' => $message . 'c id:' . $developer->id]);
        }

        return response()->json(compact('action', 'message', 'html'), 200);
    }

    /**
     * @param Developer $developer
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Exception
     */
    public function postDelete(Developer $developer)
    {
        if (!$this->user->isRoleAction('users_delete')) {
            return $this->get403Code();
        }

        $developer_name = $developer->name;
        $result = false;
        $message = 'Произощла ошибка при удалении разработчикая';

        if ($developer->delete()) {
            $result = true;
            $message = 'Вы удалил разработчика "' . $developer_name . '"';

            // Set events of create user
            EventsAdminAction::create(['events_admin_id' => 6, 'user_id' => $this->user->id, 'description' => $message]);
        }

        return response()->json(compact('result', 'message'), 200);
    }
}