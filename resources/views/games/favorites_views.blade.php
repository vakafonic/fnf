@if($games->count() > 0)
        @foreach($games as $game)
            <div class="thumb">
                <div class="thumb-inner">
                    <button class="thumb-closer"
                            onclick="mainSite.deleteMyGame({{ $game->id }}, '{{ $action }}')" type="button"><span class="icon-close"></span></button>
                    <div class="thumb-link-wrap">
                        @include('include.game_item_wrap', ['game' => $game, 'best_game' => $best_game ?? null])
                    </div>
                    @if($game->isBest($category ?? null) || (!empty($best_game) && $game->best_game == 1))
                        <div class="label-popular"><i class="icon-fire"></i></div>
                    @endif
                </div>
            </div>
        @endforeach
@else
    <div class="mygames__emptyfav">
        @if($action == 'favorites')
            <span>{{ $lang['favorite_empty'] }}</span>
            <div class="mygames__emptyfav--note">
                {{ $lang['click_button'] }} <button class="mygames__emptyfav--button"><span class="icon-star"></span>{{ $lang['to_favorites'] }} </button> {{ $lang['on_game_page_add_favorite_games'] }}
            </div>
        @else
            <span>{{ $lang['viewed_empty'] }}</span>
        @endif
    </div>
@endif