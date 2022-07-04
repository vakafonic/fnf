<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanUpDataTables extends Migration
{
    public const PAGES_TO_ADD = [
        0 => [
            'url' => '/',
            'public' => 1,
            'show_top_menu' => 0,
            'sort' => 1,
            'created_by' => 1,
        ],
        1 => [
            'url' => '/download-windows',
            'public' => 1,
            'show_top_menu' => 1,
            'sort' => 2,
            'created_by' => 1,
        ],
        2 => [
            'url' => '/download-linux',
            'public' => 1,
            'show_top_menu' => 1,
            'sort' => 2,
            'created_by' => 1,
        ],
        3 => [
            'url' => '/download-ios',
            'public' => 1,
            'show_top_menu' => 1,
            'sort' => 1,
            'created_by' => 1,
        ],
        4 => [
            'url' => '/download-android',
            'public' => 1,
            'show_top_menu' => 1,
            'sort' => 1,
            'created_by' => 1,
        ],
        5 => [
            'url' => '/download-mac',
            'public' => 1,
            'show_top_menu' => 1,
            'sort' => 1,
            'created_by' => 1,
        ],
    ];

    public const PAGES_LANGS_TO_ADD = [
        0 => [
            [
                'lang_id' => 1,
                'name' => 'Главная',
                'h2' => 'Главная',
                'menu_name' => 'Главная',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 2,
                'name' => 'Main page',
                'h2' => 'Main page',
                'menu_name' => 'Main page',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 3,
                'name' => 'Головна',
                'h2' => 'Головна',
                'menu_name' => 'Головна',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ]
        ],
        1 => [
            [
                'lang_id' => 1,
                'name' => 'FNF for Windows',
                'h2' => 'FNF for Windows',
                'menu_name' => 'FNF for Windows',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 2,
                'name' => 'FNF для Windows',
                'h2' => 'FNF для Windows',
                'menu_name' => 'FNF для Windows',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 3,
                'name' => 'FNF для Windows',
                'h2' => 'FNF для Windows',
                'menu_name' => 'FNF для Windows',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ]
        ],
        2 => [
            [
                'lang_id' => 1,
                'name' => 'FNF for Linux',
                'h2' => 'FNF for Linux',
                'menu_name' => 'FNF for Linux',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 2,
                'name' => 'FNF для Linux',
                'h2' => 'FNF для Linux',
                'menu_name' => 'FNF для Linux',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 3,
                'name' => 'FNF для Linux',
                'h2' => 'FNF для Linux',
                'menu_name' => 'FNF для Linux',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ]
        ],
        3 => [
            [
                'lang_id' => 1,
                'name' => 'FNF for IOS',
                'h2' => 'FNF for IOS',
                'menu_name' => 'FNF for IOS',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 2,
                'name' => 'FNF для IOS',
                'h2' => 'FNF для IOS',
                'menu_name' => 'FNF для IOS',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 3,
                'name' => 'FNF для IOS',
                'h2' => 'FNF для IOS',
                'menu_name' => 'FNF для IOS',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ]
        ],
        4 => [
            [
                'lang_id' => 1,
                'name' => 'FNF for Android',
                'h2' => 'FNF for Android',
                'menu_name' => 'FNF for Android',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 2,
                'name' => 'FNF для Android',
                'h2' => 'FNF для Android',
                'menu_name' => 'FNF для Android',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 3,
                'name' => 'FNF для Android',
                'h2' => 'FNF для Android',
                'menu_name' => 'FNF для Android',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ]
        ],
        5 => [
            [
                'lang_id' => 1,
                'name' => 'FNF for Mac',
                'h2' => 'FNF for Mac',
                'menu_name' => 'FNF for Mac',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 2,
                'name' => 'FNF для Mac',
                'h2' => 'FNF для Mac',
                'menu_name' => 'FNF для Mac',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ],
            [
                'lang_id' => 3,
                'name' => 'FNF для Mac',
                'h2' => 'FNF для Mac',
                'menu_name' => 'FNF для Mac',
                'description_top' => '',
                'description_buttom' => '',
                'seo_name' => '',
                'seo_description' => '',
            ]
        ],
    ];




    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET foreign_key_checks=0");

        Schema::dropIfExists('pages_ratings');
        Schema::dropIfExists('page_heroes');
        Schema::dropIfExists('page_genries');

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('genre_id');
            $table->dropColumn('rating');
            $table->dropColumn('filter_type');
        });

        DB::table('page_langs')->truncate();
        DB::table('pages')->truncate();

        foreach (static::PAGES_TO_ADD as $pageIndex => $page) {
            DB::table('pages')->insert($page);
            $pageId = DB::table('pages')->where(['url' => $page['url']])->pluck('id')[0];
            foreach (static::PAGES_LANGS_TO_ADD[$pageIndex] as $pageLang) {
                DB::table('page_langs')->insert(
                    array_merge($pageLang, ['page_id' => $pageId])
                );
            }
        }

        DB::statement("SET foreign_key_checks=1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
