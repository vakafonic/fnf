<?php
/**
 * Created by Artdevue.
 * User: artdevue - UsersController.php
 * Date: 2019-12-08
 * Time: 10:51
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\EventsAdminAction;
use App\Models\Role;
use App\Models\RolePrefix;
use App\Models\UserRole;
use App\User;
use Illuminate\Http\Request, Auth, Validator;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('auth.admin');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (!$this->user->isRoleAction('users_view')) {
            return $this->get403Code();
        }

        return view('manager.users.index');
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function allUsers(Request $request)
    {
        $rows = [];

        // Get all criteria
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $order = $request->input('order', 'asc');
        $sort = $request->input('sort', 'id');
        $status = $request->input('status', 0);
        $search = $request->input('search', null);

        $users = User::orderBy($sort, $order)->select('id', 'username', 'name', 'role', 'status')->get();
        foreach ($users as $user) {
            $user_array = $user->toArray();
            $user_array['avatar'] = $user->small_avatar;
            $rows[] = $user_array;
        }

        return response()->json($rows, 200);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newUser(User $user)
    {

        if (!$this->user->isRoleAction('users_create')) {
            return $this->get403Code();
        }

        $role_prefix = RolePrefix::all();

        return view('manager.users.new', compact('user', 'role_prefix'));
    }

    /**
     * @param Request $request
     */
    public function createUser(Request $request)
    {
        if (!$this->user->isRoleAction('users_edit')) {
            return $this->get403Code();
        }

        $input = $request->except('_method', '_token');
        $user = new User();

        $role_prefix = RolePrefix::all();

        $validator = Validator::make($input, [
            'name'     => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        /**
         * If user is manager check fild of roles
         */
        $role_array = [];

        if ($input['role'] == 2) {
            foreach ($role_prefix as $rp) {
                foreach ($rp->roles as $role) {
                    $role_array[(int)$role->id] = $rp->prefix . '_' . $role->alias;
                }
            }

            $input_key = array_keys($input);

            $role_array = array_uintersect($role_array, $input_key, "strcasecmp");

            if (count($role_array) < 1) {
                $validator->sometimes('role_select', 'required', function ($input) {
                    return $input->role <= 100;
                });
            }
        }

        if ($validator->fails()) {
            return Redirect::route('m.users.new')->withInput()->withErrors($validator);
        }

        // Create a new user
        if ($new_user = User::create(request(['name', 'username', 'email', 'password', 'role', 'status']))) {
            if ($new_user->role == 2) {
                $insert_array = [];

                foreach ($role_array as $role_id => $value) {
                    array_push($insert_array, [
                        'user_id' => $new_user->id,
                        'role_id' => $role_id
                    ]);
                }

                UserRole::insert($insert_array);
            }
        }

        $message = 'Вы удачно создали нового пользователя "' . $new_user->username . '"';

        // Set events of create user
        EventsAdminAction::create(['events_admin_id' => 1, 'user_id' => $this->user->id, 'sours_id' => $new_user->id, 'description' => $message ]);

        return redirect()->route('m.users')->with('success', 'Вы удачно создали нового пользователя');
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        if (!$this->user->isRoleAction('users_edit')) {
            return $this->get403Code();
        }

        if ($user == null) {
            return view('manager.errors.403', ['message' => 'Пользователь не найден']);
        }

        $role_prefix = RolePrefix::all();
        $role_array = [];

        if ($user->role == 2) {
            // Get All role for User
            $all_roles = UserRole::where('user_id', $user->id)->get();
            foreach ($all_roles as $role) {
                $role_array[$role->role->prefix->prefix . '_' . $role->role->alias] = 1;
            }
        }

        return view('manager.users.edit', compact('user', 'role_prefix', 'role_array'));
    }

    /**
     * Edit User
     *
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function editPost(Request $request, User $user)
    {
        if (!$this->user->isRoleAction('users_edit')) {
            return $this->get403Code();
        }

        $input = $request->except('_method', '_token');

        $role_prefix = RolePrefix::all();

        $validator_array = [
            'name'     => 'required|max:255',
            'username' => 'required|max:255|unique:users,id,' . $user->id,
            'email'    => 'required|email|max:255|unique:users,id,' . $user->id,
        ];

        $update_array = ['name' => $input['name'], 'username' => $input['username'], 'email' => $input['email'], 'role' => $input['role'], 'status' => $input['status']];

        if (!empty($input['password'])) {
            $validator_array['password'] = 'required|confirmed|min:6';
        }

        $validator = Validator::make($input, $validator_array);

        /**
         * If user is manager check fild of roles
         */
        $role_array = [];

        if ($input['role'] == 2) {
            foreach ($role_prefix as $rp) {
                foreach ($rp->roles as $role) {
                    $role_array[(int)$role->id] = $rp->prefix . '_' . $role->alias;
                }
            }

            $input_key = array_keys($input);

            $role_array = array_uintersect($role_array, $input_key, "strcasecmp");

            if (count($role_array) < 1) {
                $validator->sometimes('role_select', 'required', function ($input) {
                    return $input->role <= 100;
                });
            }
        }

        if ($validator->fails()) {
            return Redirect::route('m.users.edit', ['user' => $user->id])->withInput()->withErrors($validator);
        }

        // Delete All role of User
        UserRole::where('user_id', $user->id)->delete();

        // Update a new user
        if ($user->update($update_array)) {

            if (!empty($input['password'])) {
                $user->password = $input['password'];
                $user->save();
            }

            if ($user->role == 2) {
                $insert_array = [];

                foreach ($role_array as $role_id => $value) {
                    array_push($insert_array, [
                        'user_id' => $user->id,
                        'role_id' => $role_id
                    ]);
                }

                UserRole::insert($insert_array);
            }
        }

        $message = 'Вы удачно обновили пользователя "' . $user->username . '"';

        // Set events of create user
        EventsAdminAction::create(['events_admin_id' => 2, 'user_id' => $this->user->id, 'sours_id' => $user->id, 'description' => $message]);

        if ($input['action'] == 1) {
            return Redirect::route('m.users.edit', ['user' => $user->id])->with('success', $message);
        }

        return redirect()->route('m.users')->with('success', $message);
    }

    /**
     * Soft Delete User
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteUser(User $user)
    {
        if (!$this->user->isRoleAction('users_delete')) {
            return $this->get403Code();
        }

        $user_username = $user->username;
        $user_id = $user->id;

        $user->delete();

        $result = false;
        $message = 'Произощла ошибка при удалении пользователя';

        if ($user->trashed()) {
            $result = true;
            $message = 'Вы удалил пользователя "' . $user_username . '"';

            // Set events of create user
            EventsAdminAction::create(['events_admin_id' => 3, 'user_id' => $this->user->id, 'sours_id' => $user_id, 'description' => $message ]);
        }

        return response()->json(compact('result', 'message'), 200);
    }
}