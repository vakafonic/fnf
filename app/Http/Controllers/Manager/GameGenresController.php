<?php
/**
 * Created by Artdevue.
 * User: artdevue - GameGenresController.php
 * Date: 2020-01-02
 * Time: 10:46
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Genre;
use App\Models\GenreLang;
use App\Models\Language;
use Illuminate\Http\Request, Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class GameGenresController extends Controller
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
        if (!$this->user->isRoleAction('genres_view')) {
            return $this->get403Code();
        }

        return view('manager.games.genres.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        $rows = [];

        if (!$this->user->isRoleAction('genres_view')) {
            return response()->json($rows, 200);
        }

        // Get all criteria
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $order = $request->input('order', 'asc');
        $sort = $request->input('sort', 'sort');
        $status = $request->input('status', 0);
        $search = $request->input('search', null);

        $query = Genre::query();
        $query->join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
            ->where('genre_langs.lang_id', 1);

        $query->orderBy('genre' . ($sort == 'value' ? '_langs.' : 's.') . $sort, $order)
            ->select('genres.*');
        $genres = $query->get();

        foreach ($genres as $genre) {
            $rows[] = [
                'id'         => $genre->id,
                'pid'        => $genre->pid,
                'url'        => $genre->url,
                'public'     => $genre->public,
                'show_menu'  => $genre->show_menu,
                'show_menug' => $genre->show_menug,
                'count_game' => $genre->getCountAllAndMobiGames(),
                'sort'       => $genre->sort,
                'value'      => $genre->getName(1),
                'image'      => $genre->getUrlImage()
            ];
        }

        return response()->json($rows, 200);
    }

    /**
     * @param Genre $genre
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNew(Genre $genre)
    {
        if (!$this->user->isRoleAction('genres_view')) {
            return $this->get403Code();
        }

        $category_array = ['0' => 'Главная категория'];
        if (!empty(!$genre->id)) {
            $categories = Genre::where('id', '!=', $genre->id)->get();
        } else {
            $categories = Genre::all();
        }

        foreach ($categories as $category) {
            $category_array[$category->id] = $category->getName(1);
        }

        // Get Langs
        $langs = [];
        foreach (Language::all() as $lang) {
            $langs[$lang->code] = $genre->langsArray($lang->id);
        }

        return view('manager.games.genres.edit', compact('genre', 'category_array', 'langs'));
    }

    /**
     * @param Genre   $genre
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDelete(Genre $genre, Request $request)
    {
        if (!$this->user->isRoleAction('genres_view')) {
            return $this->get403Code();
        }

        $success = false;
        $message = 'Ошибка удаления';

        try {

            $old_image = $genre->image;

            $genre->delete();

            // Delete old image
            if (!empty($old_image) && Storage::disk('images')->exists($old_image)) {
                Storage::disk('images')->delete($old_image);
            }

            $success = true;
            $message = 'Вы удалили жанр';

        } catch (\Exception $e) {
            // do task when error
            $message = $e->getMessage();
        }

        return response()->json(compact('success', 'message'), 200);
    }

    /**
     * Save Genre
     *
     * @param Genre   $genre
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function puttNew(Genre $genre, Request $request)
    {
        if (!$this->user->isRoleAction('genres_view')) {
            return $this->get403Code();
        }

        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $input_all = $request->except('_method', '_token');
        $default_lang_code = Language::getMain('code');
        $old_image = $genre->image;

        if (!isset($input_all['url'])) {
            $input_all['url'] = $input_all['value_' . $default_lang_code];
        }

        $input_all['url'] = Str::slug($input_all['url']);

        $valid_url = [
            'required',
            'alpha_dash',
            'max:255'
        ];

        if(!in_array($genre->id, [174, 201, 202, 207])) {
            array_push($valid_url, !empty($genre->id) ? Rule::unique('seo_url')->ignore($genre->id, 'page_id') : Rule::unique('seo_url'));
        }

        $validator = Validator::make($input_all, [
            'url' => $valid_url,
            'h1_' . $default_lang_code => [
                'required',
                'max:255'
            ],
            'value' . $default_lang_code => [
                'max:255'
            ],
            'sort'                        => ['numeric', 'min:0'],
            'best_games'                  => ['numeric', 'max:100', 'min:0']
            //'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect($request->url())->withInput()->withErrors($validator);
        }

        $success_message_pre = 'создали новый';

        try {
            $genre->url = $input_all['url'];
            $genre->sort = (int)$request->input('sort', 0);
            $genre->best_games = (int)$request->input('best_games', 0);
            $genre->public = $request->input('public') ? 1 : 0;
            $genre->popular = $request->input('popular') ? 1 : 0;
            $genre->show_menu = $request->input('show_menu') ? 1 : 0;
            $genre->show_menug = $request->input('show_menug') ? 1 : 0;
            $genre->for_two = $request->input('for_two') ? 1 : 0;
            $genre->show_footer = $request->input('show_footer') ? 1 : 0;
            $genre->pid = (int)$request->input('pid', 0);
            $genre->is_for_boys = $request->input('is_for_boys') ? 1 : 0;
            $genre->is_for_girls = $request->input('is_for_girls', 0) ? 1 : 0;
            $genre->is_for_kids = $request->input('is_for_kids', 0) ? 1 : 0;

            if (!empty($input_all['id'])) {
                $genre->updated_by = $this->user->id;
                $success_message_pre = 'обновили';
            } else {
                $genre->created_by = $this->user->id;
            }

            // Set Image
            if (!empty($input_all['image']) && file_exists(public_path() . $input_all['image'])) {
                $img = Image::make(public_path() . $input_all['image']);

                if (config('site.genres.image.crop', false)) {
                    $default_width = config('site.genres.image.width', 100);
                    $default_height = config('site.genres.image.height', 100);

                    $img->fit($default_width, $default_height, function ($constraint) {
                        $constraint->upsize();
                    });
                }

                if (config('site.genres.image.watermark') && file_exists(public_path() . config('site.genres.image.patch_watermark'))) {
                    $img->insert(public_path() . config('site.genres.image.patch_watermark'));
                }

                // Create directory Genres of storage
                if (!Storage::disk('images')->exists('genres')) {

                    Storage::disk('images')->makeDirectory('genres', 0775, true); //creates directory
                }

                $extension = pathinfo(public_path($input_all['image']), PATHINFO_EXTENSION);

                // Delete old image
                if (!empty($old_image) && Storage::disk('images')->exists($old_image)) {
                    Storage::disk('images')->delete($old_image);
                }

                $img_url = 'genres/' . $genre->url . '.' . $extension;

                $img->save(Storage::disk('images')->path($img_url), config('site.genres.image.quality', 70));

                $genre->image = $img_url;
            }

            $genre->save();

            // Get Languages
            $array_filds = ['value', 'h1', 'description', 'h2', 'description_buttom', 'seo_title', 'seo_description'];
            foreach (Language::all() as $lang) {
                $language = $genre->langsArray($lang->id);
                foreach ($array_filds as $fild) {
                    if($fild == 'value') {
                        $input_all[$fild . '_' . $lang->code] = !empty($input_all[$fild . '_' . $lang->code]) ? $input_all[$fild . '_' . $lang->code] : $input_all['h1_' . $lang->code];
                    }
                    $language->$fild = $input_all[$fild . '_' . $lang->code];
                }

                $language->save();
            }

            // Set Public for parent and children categies
            $this->_setFieldCategory($genre, 'public');

            // Set chow menu for parent and children categories
            $this->_setFieldCategory($genre, 'show_menu');


        } catch (\Exception $e) {
            // do task when error
            return redirect($request->url())->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('m.games.genres')->with('success', 'Вы ' . $success_message_pre . ' жанр');
    }

    /**
     * @param       $psm
     * @param Genre $genre
     * @param       $show
     * @return \Illuminate\Http\JsonResponse
     */
    public function postPsm($psm, Genre $genre, $show)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $genre->update([$psm => $show]);

        //$this->_setFieldCategory($genre, $psm);

        return response()->json(['success' => true], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function postSort(Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $data = $request->input('data', []);
        foreach ($data as $sort => $id) {
            Genre::whereId((int)$id)->update(compact('sort'));
        }

        return response()->json('success', 200);
    }

    /**
     * @param Genre  $genre
     * @param string $field
     */
    private function _setFieldCategory($genre, $field)
    {
        // Get parents
        if ($genre->public == 1) {
            // push public all parents
            $this->_setParentsCatFiel($genre->pid, $field, 1);
        } else {
            // Push unpublic all children
            $this->_setChildrenCatField($genre->id, $field, 0);
        }

    }

    /**
     * @param $pid
     * @param $field
     * @param $value
     * @return bool
     */
    private function _setParentsCatFiel($pid, $field, $value)
    {
        if ($pid == 0) {
            return true;
        }

        $parent = Genre::where('id', (int)$pid)->first();
        if (!$parent) {
            return true;
        }
        $parent->$field = $value;
        $parent->save();

        $this->_setParentsCatFiel($parent->pid, $field, $value);
    }

    /**
     * @param $id
     * @param $field
     * @param $value
     * @return bool
     */
    private function _setChildrenCatField($id, $field, $value)
    {
        // Get childrens
        $childrens = Genre::where('pid', (int)$id);
        $count = $childrens->count();
        if ($count > 0) {
            $childrens_obj = $childrens->get();
            foreach ($childrens_obj as $obj) {
                $obj->update([$field => $value]);
                $this->_setChildrenCatField($obj->id, $field, $value);
            }
        }

        return true;
    }
}