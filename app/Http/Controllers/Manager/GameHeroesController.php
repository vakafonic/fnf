<?php
/**
 * Created by Artdevue.
 * User: artdevue - GameHeroesController.php
 * Date: 2020-01-04
 * Time: 13:17
 * Project: gamesgo.club
 */

namespace App\Http\Controllers\Manager;


use App\Models\Heroes;
use App\Models\HeroesLang;
use App\Models\Language;
use Illuminate\Http\Request, Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class GameHeroesController extends Controller
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
        /*$seopage = DB::connection('mysql2')->table('seopage')->where('seopage_parent', 8)->get();
        foreach ($seopage as $sp)
        {
            $seopagehaslang = DB::connection('mysql2')->table('seopagehaslang')->where('seopage_id', $sp->seopage_id)->first();
            if ($seopagehaslang)
            {
                $hero = new Heroes();
                $hero->old_id = $sp->seopage_id;
                $hero->url = $seopagehaslang->seopagehaslang_url;
                $hero->sort = $sp->seopage_order;
                $hero->created_by = 1;
                if ($hero->save())
                {
                    $hero_lang = new HeroesLang();
                    $hero_lang->langs_id = 1;
                    $hero_lang->heroes_id = $hero->id;
                    $hero_lang->value = $seopagehaslang->seopagehaslang_name;
                    $hero_lang->save();
                }
            }
        }*/

        if (!$this->user->isRoleAction('heroes_view')) {
            return $this->get403Code();
        }

        return view('manager.games.heroes.index');
    }

    /**
     * New or Edit Heroe
     *
     * @param Heroes $heroes
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNew(Heroes $heroes)
    {
        if (!$this->user->isRoleAction('heroes_view')) {
            return $this->get403Code();
        }

        // Get Langs
        $langs = [];
        foreach (Language::all() as $lang) {
            $langs[$lang->code] = $heroes->langsArray($lang->id);
        }

        return view('manager.games.heroes.edit', compact('heroes', 'langs'));
    }

    /**
     * Save or create Heroes
     *
     * @param Heroes  $heroes
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function putNew(Heroes $heroes, Request $request)
    {
        if (!$this->user->isRoleAction('heroes_view')) {
            return $this->get403Code();
        }

        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $input_all = $request->except('_method', '_token');
        $default_lang_code = Language::getMain('code');
        $old_image = $heroes->image;

        if (!isset($input_all['url'])) {
            $input_all['url'] = $input_all['value_' . $default_lang_code];
        }

        $input_all['url'] = Str::slug($input_all['url']);

        $validator = Validator::make($input_all, [
            'url'                         => [
                'required',
                'alpha_dash',
                'max:255',
                !empty($heroes->id) ? Rule::unique('seo_url')->ignore($heroes->id, 'page_id') : Rule::unique('seo_url'),
            ],
            'h1_' . $default_lang_code => [
                'required',
                'max:255'
            ],
            'value' . $default_lang_code => [
                'max:255'
            ]
        ]);

        if ($validator->fails()) {
            return redirect($request->url())->withInput()->withErrors($validator);
        }

        $success_message_pre = 'создали нового';

        try {
            $heroes->url = $input_all['url'];
            $heroes->sort = (int)$request->input('sort', 0);
            $heroes->public = $request->input('public') ? 1 : 0;
            $heroes->show_menu = $request->input('show_menu') ? 1 : 0;
            $heroes->show_footer = $request->input('show_footer') ? 1 : 0;
            $heroes->general = $request->input('general');

            if (!empty($input_all['id'])) {
                $heroes->updated_by = $this->user->id;
                $success_message_pre = 'обновили';
            } else {
                $heroes->created_by = $this->user->id;
            }

            // Set Image
            if (!empty($input_all['image']) && file_exists(public_path() . $input_all['image'])) {
                $img = Image::make(public_path() . $input_all['image']);

                if (config('site.genres.heroes.crop', false)) {
                    $default_width = config('site.heroes.image.width', 100);
                    $default_height = config('site.heroes.image.height', 100);
                    $img->fit($default_width, $default_height, function ($constraint) {
                        $constraint->upsize();
                    });
                }

                if (config('site.heroes.image.watermark') && file_exists(public_path() . config('site.genres.heroes.patch_watermark'))) {
                    $img->insert(public_path() . config('site.heroes.image.patch_watermark'));
                }

                // Create directory Genres of storage
                if (!Storage::disk('images')->exists('heroes')) {

                    Storage::disk('images')->makeDirectory('heroes', 0775, true); //creates directory
                }

                $extension = pathinfo(public_path($input_all['image']), PATHINFO_EXTENSION);

                // Delete old image
                if (!empty($old_image) && Storage::disk('images')->exists($old_image)) {
                    Storage::disk('images')->delete($old_image);
                }

                $img_url = 'heroes/' . $heroes->url . '.' . $extension;

                $img->save(Storage::disk('images')->path($img_url), config('site.heroes.image.quality', 70));

                $heroes->image = $img_url;
            }

            $heroes->save();

            // Get Languages
            $array_filds = ['value', 'h1', 'description', 'h2', 'description_buttom', 'seo_title', 'seo_description'];
            foreach (Language::all() as $lang) {
                $language = $heroes->langsArray($lang->id);
                foreach ($array_filds as $fild) {
                    if($fild == 'value') {
                        $input_all[$fild . '_' . $lang->code] = !empty($input_all[$fild . '_' . $lang->code]) ? $input_all[$fild . '_' . $lang->code] : $input_all['h1_' . $lang->code];
                    }
                    $language->$fild = $input_all[$fild . '_' . $lang->code];
                }

                $language->save();
            }

        } catch (\Exception $e) {
            // do task when error
            return redirect($request->url())->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('m.games.heroes')->with('success', 'Вы ' . $success_message_pre . ' героя');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        $rows = [];

        if (!$this->user->isRoleAction('heroes_view')) {
            return response()->json($rows, 200);
        }

        // Get all criteria
        $order = $request->input('order', 'asc');
        $sort = $request->input('sort', 'sort');
        $status = $request->input('status', 0);
        $search = $request->input('search', null);

        //$heroes = Heroes::orderBy($sort, $order)->get();

        $query = Heroes::query();
        $query->join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
            ->where('heroes_langs.langs_id', 1);

        $query->orderBy('heroes' . ($sort == 'value' ? '_langs.' : '.') . $sort, $order);

        $query->select('heroes.*', 'heroes_langs.value');
        $heroes = $query->get();

        foreach ($heroes as $hero) {
            $trans_array = [];
            foreach ($hero->langs as $lang) {
                $trans_array[] = $lang->lang->code . ': ' . $lang->value;
            }

            $rows[] = [
                'id'         => $hero->id,
                'sort'       => $hero->sort,
                'public'     => $hero->public,
                'show_menu'  => $hero->show_menu,
                'value'      => $hero->value,
                'count_game' => $hero->getCountMobiAndAllGames(),
                'trans'      => '<mark>' . implode('</mark>, <mark>', $trans_array) . '</mark>',
                'image'      => $hero->getUrlImage(),
                'url'        => $hero->url
            ];
        }

        return response()->json($rows, 200);
    }

    /**
     * @param Heroes  $heroes
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete(Heroes $heroes, Request $request)
    {
        $success = false;
        $message = 'Ошибка удаления';
        try {

            $old_image = $heroes->image;
            $heroes->delete();

            // Delete old image
            if (!empty($old_image) && Storage::disk('images')->exists($old_image)) {
                Storage::disk('images')->delete($old_image);
            }

            $success = true;
            $message = 'Вы удалили героя';

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        return response()->json(compact('success', 'message'), 200);
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
            Heroes::whereId((int)$id)->update(compact('sort'));
        }

        return response()->json('success', 200);
    }

    /**
     * @param string $psm
     * @param Heroes $heroes
     * @param int    $show
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function postPsm($psm, Heroes $heroes, $show)
    {
        if ($this->user->role != 1) {
            return $this->get403Code();
        }

        $heroes->update([$psm => $show]);

        return response()->json(['success' => true], 200);
    }

    /**
     * @param null $trans_id int
     * @return array
     */
    private function _langsArray($hero_id = null)
    {
        $langs_obj = Language::all();
        $langs = [];
        foreach ($langs_obj as $lang) {
            $value = '';

            if (!empty($hero_id)) {
                // Get trans
                $value = HeroesLang::where('heroes_id', $hero_id)->where('langs_id', $lang->id)->value('value');
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