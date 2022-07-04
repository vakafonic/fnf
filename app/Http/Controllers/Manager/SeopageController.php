<?php
/**
 * Created by Artdevue.
 * User: artdevue - SeopageController.php
 * Date: 2020-02-23
 * Time: 00:58
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Genre;
use App\Models\Heroes;
use App\Models\Language;
use App\Models\Seopage;
use App\Models\SeopagesGenre;
use App\Models\SeopagesHeroe;
use Carbon\Carbon;
use Illuminate\Http\Request, Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SeopageController extends Controller
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
        parent::__construct();
        Carbon::setLocale('ru');
        setlocale(LC_TIME, 'ru');
    }

    /**
     * View all pages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function index()
    {
        return view('manager.seopage.index');
    }

    public function getAll(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $order = $request->input('order', 'asc');
        $sort = $request->input('sort', 'sort');
        $search = $request->input('search', null);

        $rows = [];
        $query = Seopage::query();
        $query->join('seopages_langs', 'seopages.id', '=', 'seopages_langs.seopage_id')
            ->where('seopages_langs.lang_id', 1);
        // Query of Search
        if (!empty($search)) {
            $query->where('seopages_langs.value', 'LIKE', "%$search%");
        }

        $total = $query->count();

        $query->orderBy('seopages.' . $sort, $order)
            ->skip($offset)
            ->take($limit);
        $seopages = $query->get(['seopages.id', 'seopages.url', 'seopages.comment', 'seopages_langs.value', 'seopages.public', 'seopages.created_at', 'seopages.sort']);

        foreach ($seopages as $seopage) {
            $rows[] = [
                'id'         => $seopage->id,
                'comment'    => $seopage->comment,
                'value'      => $seopage->value,
                'public'     => $seopage->public,
                'created_at' => $seopage->created_at->formatLocalized('%d %B %Y в %H:%M'),
                'sort'       => $seopage->sort,
                'url'        => $seopage->url
            ];
        }

        return response()->json(compact('rows', 'total'), 200);
    }

    /**
     * @param Seopage $seopage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNew(Seopage $seopage)
    {
        // Get Langs
        $langs = [];
        foreach (Language::all() as $lang) {
            $langs[$lang->code] = $seopage->langsArray($lang->id);
        }

        return view('manager.seopage.new', compact('seopage', 'langs'));
    }

    public function putNew(Seopage $seopage, Request $request)
    {
        $input_all = $request->except('_method', '_token');
        $default_lang_code = Language::getMain('code');

        if (!isset($input_all['url'])) {
            $input_all['url'] = trim($input_all['value_' . $default_lang_code]);
        }
        $input_all['url'] = Str::slug(trim($input_all['url']));

        $validator = Validator::make($input_all, [
            'url'                         => [
                'required',
                'alpha_dash',
                'max:255',
                !empty($seopage->id) ? Rule::unique('seo_url')->ignore($seopage->id, 'page_id') : Rule::unique('seo_url'),
            ],
            'value_' . $default_lang_code => [
                'required',
                'max:255'
            ],
            'sort'                        => ['numeric', 'min:0']
        ]);

        if ($validator->fails()) {
            return redirect($request->url())->withInput()->withErrors($validator);
        }

        if ((!isset($input_all['genries']) || !is_array($input_all['genries'])) && (!isset($input_all['heroes']) || !is_array($input_all['heroes']))) {
            return redirect($request->url())->withInput()->with('error', 'Нужно выбрать хоть один жанр или героя');
        }

        $success_message_pre = 'создали новую';

        try {

            $seopage->url = $input_all['url'];
            $seopage->comment = trim($input_all['comment']);
            $seopage->sort = (int)$request->input('sort', 0);
            $seopage->public = $request->input('public') ? 1 : 0;

            if (!empty($input_all['id'])) {
                $seopage->updated_by = $this->user->id;
                $success_message_pre = 'обновили';
            } else {
                $seopage->created_by = $this->user->id;
            }

            $seopage->save();

            // Get Languages
            $array_filds = ['value', 'description', 'h2', 'description_buttom', 'seo_title', 'seo_description'];
            foreach (Language::all() as $lang) {
                $language = $seopage->langsArray($lang->id);
                foreach ($array_filds as $fild) {
                    $language->$fild = $input_all[$fild . '_' . $lang->code];
                }

                $language->save();
            }

            // Delete all genries for this seo page
            $old_genries = SeopagesGenre::whereSeopagesId($seopage->id)->get();
            if ($old_genries->count() > 0) {
                foreach ($old_genries as $old_genry) {
                    $old_genry->delete();
                }
            }
            // Set Genries
            $genries = $request->input('genries', []);
            if (is_array($genries) && count($genries) > 0) {
                foreach ($genries as $index => $genry) {
                    if (!empty((int)$genry)) {
                        $new_genry = new SeopagesGenre();
                        $new_genry->seopages_id = $seopage->id;
                        $new_genry->genry_id = (int)$genry;
                        $new_genry->sort = $index;
                        $new_genry->save();
                    }
                }
            }

            // Delete all heroes for this seo page
            $old_heroes = SeopagesHeroe::whereSeopagesId($seopage->id)->get();
            if ($old_heroes->count() > 0) {
                foreach ($old_heroes as $old_heroy) {
                    $old_heroy->delete();
                }
            }
            // Set Heroy
            $heroes = $request->input('heroes', []);
            if (is_array($heroes) && count($heroes) > 0) {
                foreach ($heroes as $index => $heroy) {
                    if (!empty((int)$heroy)) {
                        $new_heroy = new SeopagesHeroe();
                        $new_heroy->seopages_id = $seopage->id;
                        $new_heroy->heroes_id = (int)$heroy;
                        $new_heroy->sort = $index;
                        $new_heroy->save();
                    }
                }
            }

        } catch (\Exception $e) {
            // do task when error
            return redirect($request->url())->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('m.seopage.index')->with('success', 'Вы ' . $success_message_pre . ' СЕО страницу');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenries(Request $request)
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHeroes(Request $request)
    {
        $out = [];
        $search = $request->get('search');

        if (!empty($search)) {

            $heroes = Heroes::join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
                ->where('heroes_langs.langs_id', 1)
                ->where('heroes.public', 1)
                ->where('heroes_langs.value', 'LIKE', '%' . $search . '%')
                ->orderBy('heroes.sort', 'ASC')
                ->take(config('site.menu.view_categories'))
                ->select('heroes.id', 'heroes.image', 'heroes.url', 'heroes_langs.value')
                ->get();

            foreach ($heroes as $heroy) {
                $out[] = ['id' => $heroy->id, 'value' => $heroy->value, 'image' => $heroy->getUrlImage()];
            }
        }

        return response()->json($out, 200);
    }

    /**
     * @param Seopage $seopage
     * @param         $show
     * @return \Illuminate\Http\JsonResponse
     */
    public function postPublic(Seopage $seopage, $show)
    {
        $seopage->update(['public' => $show]);

        return response()->json(['success' => true], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSort(Request $request)
    {
        $data = $request->input('data', []);
        foreach ($data as $sort => $id) {
            Seopage::whereId((int)$id)->update(compact('sort'));
        }

        return response()->json('success', 200);
    }

    public function postDelete(Seopage $seopage)
    {
        $success = false;
        $message = 'Ошибка удаления';
        try {
            $seopage->delete();

            $success = true;
            $message = 'Вы удалили СЕО страницу';

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        return response()->json(compact( 'success', 'message'), 200);
    }
}