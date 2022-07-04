@foreach($games as $game)
    @include('include.game_item_wrap', ['game' => $game])
@endforeach