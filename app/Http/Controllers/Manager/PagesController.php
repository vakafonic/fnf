<?php
/**
 * Created by Artdevue.
 * User: artdevue - PagesController.php
 * Date: 2020-01-23
 * Time: 00:43
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Genre;
use App\Models\Heroes;
use App\Models\Language;
use App\Models\Page;
use App\Models\PageGenries;
use Illuminate\Http\Request, Validator;
use Illuminate\Validation\Rule;

class PagesController extends Controller
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

        return view('manager.pages.index');
    }

    /**
     * Return json pages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $rows = [];

        $pages = Page::all();
        foreach ($pages as $page) {
            $rows[] = [
                'id'            => $page->id,
                'name'          => $page->getName(1),
                'public'        => $page->public,
                'show_top_menu' => $page->show_top_menu
            ];
        }

        return response()->json($rows, 200);
    }

    /**
     * @param Page $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit(Page $page)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        // Get Langs
        $langs = [];
        foreach (Language::all() as $lang) {
            $langs[$lang->code] = $page->langsArray($lang->id);
        }

        return view('manager.pages.edit', compact('page', 'langs'));
    }

    /**
     * @param Page    $page
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function putEdit(Page $page, Request $request)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $input_all = $request->except('_method', '_token');
        $default_lang_code = Language::getMain('code');

        $validator_array = ['sort' => 'integer', 'min:0', 'max:9999999999'];
        foreach (Language::all() as $lang) {
            $langs[$lang->code] = $page->langsArray($lang->id);
            $validator_array['name_' . $lang->code] = ['max:255', $default_lang_code == $lang->code ? 'required' : null];
            $validator_array['menu_name_' . $lang->code] = ['max:255'];
            $validator_array['h2_' . $lang->code] = ['max:255'];
        }

        $validator = Validator::make($input_all, $validator_array);

        if ($validator->fails()) {
            return redirect($request->url())->withInput()->withErrors($validator);
        }

        $success_message_pre = 'создали новую';

        try {

            $page->sort = (int)$request->input('sort', 0);
            $page->public = $request->input('public') ? 1 : 0;
            $page->show_top_menu = $request->input('show_top_menu') ? 1 : 0;

            if (!empty($input_all['id'])) {
                $page->updated_by = $this->user->id;
                $success_message_pre = 'обновили';
            } else {
                $page->created_by = $this->user->id;
            }

            $page->save();

            // Get Languages
            $array_filds = ['name', 'h2', 'menu_name', 'description_top', 'description_buttom', 'seo_name', 'seo_description'];
            foreach (Language::all() as $lang) {
                $language = $page->langsArray($lang->id);
                foreach ($array_filds as $fild) {
                    $language->$fild = $input_all[$fild . '_' . $lang->code];
                }

                $language->save();
            }

            // Delete all categories for this page
            $old_categories = PageGenries::whereIdPage($page->id)->get();
            if ($old_categories->count() > 0) {
                foreach ($old_categories as $old_category) {
                    $old_category->delete();
                }
            }
            // Set Categories
            $categories = $request->input('categories', []);
            if (is_array($categories) && count($categories) > 0) {
                foreach ($categories as $index => $category) {
                    if (!empty((int)$category)) {
                        $new_category = new PageGenries();
                        $new_category->id_page = $page->id;
                        $new_category->id_genry = (int)$category;
                        $new_category->sort = $index;
                        $new_category->save();
                    }
                }
            }
//      Due to structure change some class were deleted, just commenting old code
//            // Delete all heroes for this page
//            $old_heroes = PageHeroes::whereIdPage($page->id)->get();
//            if ($old_heroes->count() > 0) {
//                foreach ($old_heroes as $old_heroy) {
//                    $old_heroy->delete();
//                }
//            }
//            // Set Heroes
//            $heroes = $request->input('heroes', []);
//            if (is_array($heroes) && count($heroes) > 0) {
//                foreach ($heroes as $index => $heroy) {
//                    if (!empty((int)$heroy)) {
//                        $new_heroy = new PageHeroes();
//                        $new_heroy->id_page = $page->id;
//                        $new_heroy->id_heroy = (int)$heroy;
//                        $new_heroy->sort = $index;
//                        $new_heroy->save();
//                    }
//                }
//            }

        } catch (\Exception $e) {
            // do task when error
            return redirect($request->url())->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('m.pages.index')->with('success', 'Вы ' . $success_message_pre . ' страницу');
    }

    /**
     * @param Page    $page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories(Page $page, Request $request)
    {
        $out = [];
        $search = $request->get('search');

        if (!empty($search)) {

            $genries = Genre::join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
                ->where('genre_langs.lang_id', 1)
                ->where('genres.public', 1)
                ->where('genre_langs.value', 'LIKE', '%' . $search . '%')
                ->orderBy('genres.sort', 'ASC')
                ->take(config('site.menu.view_categories'))
                ->select('genres.id', 'genres.image', 'genres.url', 'genre_langs.value')
                ->get();

            foreach ($genries as $genry) {
                $out[] = ['id' => $genry->id, 'value' => $genry->value, 'image' => $genry->getUrlImage()];
            }
        }

        return response()->json($out, 200);
    }

    /**
     * @param Page    $page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHeroes(Page $page, Request $request)
    {
        $out = [];
        $search = $request->get('search');

        if (!empty($search)) {

            $genries = Heroes::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
                ->where('heroes_langs.langs_id', 1)
                ->where('heroes.public', 1)
                ->where('heroes_langs.value', 'LIKE', '%' . $search . '%')
                ->orderBy('heroes.sort', 'ASC')
                ->take(config('site.menu.view_categories'))
                ->select('heroes.id', 'heroes.image', 'heroes.url', 'heroes_langs.value')
                ->get();

            foreach ($genries as $genry) {
                $out[] = ['id' => $genry->id, 'value' => $genry->value, 'image' => $genry->getUrlImage()];
            }
        }

        return response()->json($out, 200);
    }
}