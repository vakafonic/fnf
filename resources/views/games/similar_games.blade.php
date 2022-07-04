@if($games->count() > 0)
    @foreach($games as $game)
        @if(isset($game_id))
            @if($game_id != $game->id)
                <div class="thumb">
                    <div class="thumb-inner">
                        <div class="thumb-link-wrap">
                            @include('include.game_item_wrap', ['game' => $game, 'best_game' => $best_game ?? null])
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="thumb">
                <div class="thumb-inner">
                    <div class="thumb-link-wrap">
                        @include('include.game_item_wrap', ['game' => $game, 'best_game' => $best_game ?? null])
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
