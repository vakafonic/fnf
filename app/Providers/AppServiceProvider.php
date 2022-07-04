<?php

namespace App\Providers;

use App\Console\Commands\TranslateInject;
use App\Models\Genre;
use App\Models\Heroes;
use App\Models\Language;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

	protected $globFooterGenres;
	protected $globFooterHeroes;

    public function boot()
    {
        view()->composer('*', function ($view) {
            $locale = Language::where('code', '=', app()->getLocale())->get()->toArray();
            $locale = reset($locale);
		if(!isset($this->globFooterGenres)) {
                	$this->globFooterGenres = Genre::rightJoin('games_genres', 'genres.id', '=', 'games_genres.genry_id')
	                ->join('genre_langs', 'genres.id', '=', 'genre_langs.genre_id')
        	        ->where('genre_langs.lang_id', $locale['id'])
                	->where('genres.public', 1)
	                ->where('genres.show_footer', '=', 1)
        	        ->groupBy('genres.id') 
                	->orderBy('genres.created_at', 'ASC')
	                ->select('genre_langs.value', 'genres.url')
        	        ->get()
	                ->toArray();
              	}
		$footerGenres = $this->globFooterGenres;
		if(!isset($this->globFooterHeroes)) {
                        $this->globFooterHeroes = Heroes::rightJoin('games_heroes', 'heroes.id', '=', 'games_heroes.heroy_id')
                ->join('heroes_langs', 'heroes.id', '=', 'heroes_langs.heroes_id')
                ->where('heroes_langs.langs_id', $locale['id'])
                ->where('heroes.public', 1)
                ->where('show_footer', '=', 1)
                ->groupBy('heroes.id')
                ->orderBy('heroes.created_at', 'ASC')
                ->select('heroes_langs.value', 'heroes.url')
                ->get()
                ->toArray();
		}
		$footerHeroes = $this->globFooterHeroes;
            $view->with(['footerHeroes' => $footerHeroes, 'footerGenres' => $footerGenres]);

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            TranslateInject::class
        );
    }
}
