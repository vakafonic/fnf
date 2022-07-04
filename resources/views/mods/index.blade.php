@extends('layouts.app')
@section('content')

    <main class="page page_categories">

        <div class="b-categories">
            <div class="b-categories__container _container">
                <h2 class="b-categories__title title text-center">{{$lang['Сategories']}}</h2>
                <div class="b-categories__text text text_gray">
                    {{$lang['Сategories_text']}}
                </div>

                <div class="b-categories__sort">
                    <a class="b-categories__sort-item @if(!$new) _active @endif " @if($new)  href="{{route('mods')}}" @endif>{{$lang['Popular']}}</a>
                    <a class="b-categories__sort-item @if($new) _active @endif "  @if(!$new)  href="{{route('new-mods')}}" @endif>{{$lang['new']}}</a>
                </div>

                <div class="b-grid b-grid_active">
                    <div class="b-grid__container _container">
                        <div class="b-grid__content">
                            @foreach($games as $game)
                                @include('include.game_item_wrap', ['game' => $game])
                            @endforeach
                        </div>
                    </div>
                </div>


                @if($games->lastPage() > 1)
                    {{ $games->links('component.pagination') }}
                @endif

                {!! $lang['Mods_text_html'] !!}
            </div>
        </div>

    </main>

@endsection
@section('script')

@endsection
