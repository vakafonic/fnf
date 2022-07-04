@foreach($get_heroes_via_game_id as $hero)
        @include('include.hero_item', compact('hero'))
@endforeach
@forelse($get_via_game_id as $genry)
        @include('include.genry_item_menu', compact('genry'))
@empty
@endforelse