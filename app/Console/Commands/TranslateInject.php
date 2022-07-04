<?php

namespace App\Console\Commands;

use App\Models\Translation;
use App\Models\TranslationLang;
use Illuminate\Console\Command;

class TranslateInject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:inject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inject translations for not existing entities';


    public const TRANSLATIONS = [
        ['fnf_pc_download', 'Friday night funkin pc download'],
        ['fnf_mainpage_descr', 'Friday night funkin is the place where the beat count! Dance and have fun with FNF PC game from PC too!'],
        ['download', 'Download'],
        ['play_online', 'Play online'],
        ['fnf_mods_online', 'FNF mods online'],
        ['fnf_show_more', 'Show more'],
        ['faq', 'Faq'],
        ['faq_title_1', 'How i can translate site into my language?'],
        ['faq_title_2', 'Where i can find support?'],
        ['faq_title_3', 'Can i download game on Android?'],
        ['faq_title_4', 'How i can translate site into my language?'],
        ['faq_title_5', 'How to play?'],
        ['faq_desc_1', 'faq_desc_1'],
        ['faq_desc_2', 'faq_desc_2'],
        ['faq_desc_3', 'faq_desc_3'],
        ['faq_desc_4', 'faq_desc_4'],
        ['faq_desc_5', 'faq_desc_5'],
        ['FNF_mods', 'FNF mods'],
        ['New_mods', 'New mods'],
        ['Home', 'Home'],
        ['Download_FNF', 'Download FNF'],
        ['Week_7', 'Week 7'],
        ['footer_text_fnf', 'FridayNightFunkin.net is an online gaming website. We provide large collection of FNF Mods to our users. Almost all Friday Night Funkin Mods are available on our site. These FNеF Online Mods and other games on this website are completely free to play.'],
        ['Сategories', 'Сategories'],
        ['Сategories_text', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book.'],
        ['Popular', 'Popular'],
        ['New', 'New'],
        ['Previous', 'Previous'],
        ['Next', 'Next'],
        ['Play', 'Play'],
        ['Mods_text_html', '<div class="b-categories__paragraph">
                    <div class="subtitle">Latest FNF Mods</div>
                    <div class="text">
                        Welcome FNF Fans, to a place where thousands of FNF Mods are available for online playing and downloading.
                        Do you love playing Friday Night Funkin Online? If YES then you are in right place. This site provides all
                        Friday Night Funkin Mods Online for free. You can also Download Friday Night Funkin Mod for your PC for
                        offline playing. Currently, Friday Night Funkin Mod Download links are only available for the Windows
                        Operating system but soon we will make FNF Mods available for the MAC Operating system. There are
                        thousands of FNF Mods are available for the Friday Night Funkin game, so it is very difficult to choose
                        any specific mod. There are FNF Mods that will make the game look like comedy and on other hand, there are
                        mods that will make this simple game very creative. There are also mods that will add a different
                        aesthetic to a simple FNF Online game. You can play Friday Funkin Mods in almost every Web Browser without
                        any problems.
                    </div>
                </div>
                <div class="b-categories__paragraph">
                    <div class="subtitle">Keyboard Controls:</div>
                    <div class="text">
                        You can play FNF online in your Web Browser using WASD keys or Up, Down, Left, Right Arrows keys. After
                        you die in-game, You can restart the game using the Space button or Mouse click on the screen. You can
                        change the difficulty of FNF before playing in Menu using Left/A and Right/D keys. The Gameplay of the FNF
                        game is very simple. You just have to press the Key showing on your screen when Two Arrow meets each
                        other. Sometimes you have long Arrows, In that case, you have to press and hold the specific keys. </div>
                </div>
                <div class="b-categories__paragraph">
                    <div class="subtitle">Plot Of Friday Night Funkin Game:</div>
                    <div class="text">
                        In Friday Night Funkin Game, You are Boyfriend and you love a Girl named Girlfriend. The Girl can\'t date
                        anyone before getting her Father\'s Permission. This looks simple but it is not. The Father wants a decent
                        guy with music knowledge so He challenges you to rap battle duel. The only way to look good in eyes of
                        Gurfiend\'s Father is by defeating all guys in Rap Battle. If you can beat all your rivals then your
                        Girlfriend is yours. Can you become a Friday Night Funkin\' Champion? </div>
                </div>'],
        ['Description', 'Description'],
        ['Game_controls', 'Game controls'],
        ['Comments', 'Comments'],
        ['Credits', 'Credits'],
        ['enter_your_name', 'Enter your name'],
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (static::TRANSLATIONS as $tr) {
            $t = Translation::where(['key_lang' => $tr[0]])->first();
            if ($t === null) {
                $t = new Translation(['key_lang' => $tr[0]]);
                $t->save();
            }

            for ($i = 1; $i <= 3; $i++) {
                $tl = $t->langs->where('language_id', '=', $i)->first();
                if ($tl === null) {
                    $tl = new TranslationLang(['language_id' => $i, 'name' => $tr[1]]);
                    $t->langs()->save($tl);
                } else {
                    $tl->name = $tr[1];
                    $tl->save();
                }
            }

        }
    }
}
