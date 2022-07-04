<div class="games">
    <button class="circle-bg-closer" type="button">
        <span class="icon-close"></span>
    </button>
    <div class="container">
        <div class="games-inner">
            <div class="games-headline">
                <div class="games-title"><i class="icon-gamepad"></i><span class="games-title-text">{{ $lang['my_games'] }}</span></div>
                <div class="tabs-wrap">
                    <div class="tabs">
                        <button class="tab-first js-games-tab is-current" type="button" data-toggler="watched">
                            <span class="tab-text">{{ $lang['viewed'] }}</span>
                        </button>
                        <button class="tab-last js-games-tab" type="button" data-toggler="chosen">
                            <span class="tab-text">{{ $lang['favorites'] }}</span>
                        </button>
                    </div>
                </div>
            </div>
            @if (Auth::guest())
            <p class="games-tip">{{ $lang['do_not_lose_your_games'] }} - <a class="auth-opener" href="#">{{ $lang['come_in'] }}</a> {{ $lang['or'] }} <a class="register-opener" href="#">{{ $lang['register'] }}</a>, {{ $lang['to_keep_them_forever'] }}.</p>
            @endif
            <div class="games-bl is-open" data-toggle="watched" id="sectionViews">
                {{--load via ajax--}}
            </div>
            <div id="sectionFavorites" class="games-bl" data-toggle="chosen">
                {{--load via ajax--}}
            </div>
        </div>
    </div>
</div>