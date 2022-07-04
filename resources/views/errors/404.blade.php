@extends('layouts.app')
@section('content')

    <section>
        <div class="container">
            <div class="found-page">
                <div class="search-no">{{ $lang['error'] }} <span class="is-primary-colored">404</span></div>
                <h1 class="title-big"> {{ $lang['page_not_found'] }}</h1>
                <div class="search-empty-light">{{ $lang['address_is_incorrectly'] }}.</div>
                {{ Form::open(['route' => 'search_prev',  'id' => 'searchForm', 'method' => 'post']) }}
                {!! Form::hidden('_token', csrf_token()) !!}
                    <div class="found-form-inner">
                        {!! Form::text('search', old('search'), ['class' => 'found-field',  'required' => 'required', 'placeholder' => $lang['looking_for']]) !!}
                        <button class="primary-btn" type="submit">
                            <span class="primary-btn-text">{{ $lang['to_find'] }}</span>
                        </button>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </section>
    <section>
        <?php
        $gamesController = new App\Http\Controllers\GameController;
        $bestGames = $gamesController->modelGames(true, 10);
        ?>
        <div class="container">
            <div class="headline">
                <h1 class="title">{{ $lang['best_games'] }}</h1>
            </div>
            <div class="thumbs">
                @foreach($bestGames as $game)
                    <div class="thumb">
                        <div class="thumb-inner">
                            <div class="thumb-link-wrap">
                                @include('include.game_item_wrap', ['game' => $game, 'best_game' => $best_game ?? null])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{route('games', ['url' => 'best-games'])}}" class="primary-btn">
                <span>{{ $lang['show_all'] }}</span>
            </a>
        </div>
    </section>
@endsection