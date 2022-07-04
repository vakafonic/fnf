<?php
/**
 * Created by Artdevue.
 * User: artdevue - SitemapController.php
 * Date: 2020-03-28
 * Time: 11:54
 * Project: gamesgo.club
 */

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Heroes;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redirect;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SitemapController extends BaseController
{
    private $current_local;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->current_local = LaravelLocalization::getCurrentLocale();

        if (substr(request()->getRequestUri(), 0, 4) == '/en/') {
            header("Location: " . substr(request()->getRequestUri(), 3),TRUE,301);
            die();
        }

        if($this->current_local == 'en') {
            $this->current_local = '';
        } else {
            // Get Lang and check is active
            if((int) Language::whereCode($this->current_local)->value('status') == 0) {
                header("Location: " . substr(request()->getRequestUri(), 3),TRUE,301);
                die();
            }
        }

    }

    public function index()
    {
        $sitemap_array = [];

        // Get Home
        $pages = Page::wherePublic(1)->select('url', 'updated_at')->get();
        foreach ($pages as $page) {
            $sitemap_array[] = [
                'loc' => url($this->current_local, $page->url == '/' ? null : $page->url ),
                'lastmod' => $page->updated_at->tz('UTC')->toAtomString(),
                /*'changefreq' => 'weekly',
                'priority' => 0.6*/
            ];
        }

        // Get last modified Genre
        $last_genry = Genre::wherePublic(1)->orderBy('updated_at', 'DESC')->select('updated_at')->first();
        $sitemap_array[] = [
            'loc' => $this->getUrlLocalMap('genres.xml'),
            'lastmod' => $last_genry->updated_at->tz('UTC')->toAtomString(),
            /*'changefreq' => 'weekly',
            'priority' => 0.6*/
        ];

        // Get last modified Hero
        $last_hero = Heroes::wherePublic(1)->orderBy('updated_at', 'DESC')->select('updated_at')->first();
        $sitemap_array[] = [
            'loc' => $this->getUrlLocalMap('heroes.xml'),
            'lastmod' => $last_hero->updated_at->tz('UTC')->toAtomString(),
            /*'changefreq' => 'weekly',
            'priority' => 0.6*/
        ];

        // Get last modified Game
        $last_game = Game::wherePublic(1)->orderBy('updated_at', 'DESC')->select('updated_at')->first();
        $sitemap_array[] = [
            'loc' => $this->getUrlLocalMap('games.xml'),
            'lastmod' => $last_game->updated_at->tz('UTC')->toAtomString(),
            /*'changefreq' => 'weekly',
            'priority' => 0.6*/
        ];

        return $this->returnXml($sitemap_array);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function genres()
    {
        $sitemap_array = [];

        $genries = Genre::wherePublic(1)->select('url', 'updated_at')->get();
        foreach ($genries as $genry) {
            $sitemap_array[] = $this->toArrayMap($genry, 'weekly', 0.7);
        }

        return $this->returnXml($sitemap_array);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function heroes()
    {
        $sitemap_array = [];

        $genries = Heroes::wherePublic(1)->select('url', 'updated_at')->get();
        foreach ($genries as $genry) {
            $sitemap_array[] = $this->toArrayMap($genry, 'weekly', 0.7);
        }

        return $this->returnXml($sitemap_array);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function games()
    {
        $sitemap_array = [];

        $genries = Game::wherePublic(1)->select('url', 'updated_at')->get();
        foreach ($genries as $genry) {
            $sitemap_array[] = $this->toArrayMap($genry, 'weekly', 0.8);
        }

        return $this->returnXml($sitemap_array);
    }

    /**
     * @param string $url
     * @return string
     */
    private function getUrlLocalMap(string $url) : string
    {
        return config('app.url') . ($this->current_local != '' ? $this->current_local . '/' : '') . $url;
    }

    /**
     * @param object $this_object
     * @param string $changefreq
     * @param float  $priority
     * @return array
     */
    private function toArrayMap(object $this_object, $changefreq = 'weekly', $priority = 0.6) : array
    {
        return [
            'loc' => url($this->current_local, $this_object->url ),
            'lastmod' => $this_object->updated_at->tz('UTC')->toAtomString(),
            /*'changefreq' => $changefreq,
            'priority' => $priority*/
        ];
    }

    /**
     * @param array $sitemap_array
     * @return \Illuminate\Http\Response
     */
    private function returnXml(array $sitemap_array)
    {
        return response()->view('sitemap.index', compact('sitemap_array'))
            ->header('Content-Type', 'text/xml')->header('charset','utf-8');
    }
}