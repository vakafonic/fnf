@extends('layouts.app')
@section('seo_title', $lang['search'] . ' «' .  $word . '»')
@section('content')
    <section>
        <div class="container">
            <div class="breadcrumbs">
                <a class="breadcrumbs-btn" href="{{ route('index') }}">
                    <span class="icon-arrow-left"></span>
                    <span class="breadcrumbs-btn-text">{{ $lang['online_games'] }}</span>
                </a>
                <div class="breadcrumbs-nav-wrap">
                    <nav>
                        <ol class="breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">
                            <li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a class="breadcrumbs-link" itemprop="item" href="{{ route('index') }}">
                                    <span itemprop="name">{{ $lang['online_games'] }}</span>
                                </a>
                                <meta itemprop="position" content="1" />
                            </li>
                            <li class="breadcrumbs-item">{{ $lang['search'] }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="headline">
                <h1 class="title-big"> {{ $lang['search'] }} «<span class="search-target">{{ $word }}</span>»</h1>
            </div>
            @if($genres->count() > 0)
                <div class="result-line">
                    <h3 class="title-tiny"><i>{{ $genres->count() }}</i> {{ plural($genres->count(), $lang['plural_category']), $current_locale->id }}</h3>
                <div class="categories-second">
                    @foreach($genres as $index => $genry)
                        @if($index == 4 && $genres->count() > 5)
                            <div class="category has-btn">
                                <button class="show-more-btn" type="button">
                                    <span class="show-more-text">{{ $lang['show_all'] }}</span>
                                </button>
                            </div>
                        @endif
                            @include('include.genry_item_menu', compact('genry'))
                    @endforeach
                </div>
                </div>
            @endif
            @if($heroes->count() > 0)
                <div class="result-line">
                    <h3 class="title-tiny"><i>{{ $heroes->count() }}</i> {{ plural($heroes->count(), $lang['plural_heroes']), $current_locale->id }}</h3>
                <div class="categories-second">
                    @foreach($heroes as $index => $hero)
                        @if($index == 4 && $heroes->count() > 5)
                            <div class="category has-btn">
                                <button class="show-more-btn" type="button">
                                    <span class="show-more-text">{{ $lang['show_all'] }}</span>
                                </button>
                            </div>
                        @endif
                            @include('include.hero_item_menu', compact('hero'))
                    @endforeach
                </div>
                </div>
            @endif
            @if($games->total() > 0)
                <div class="result-line">
                    <h3 class="title-tiny"><i>{{ $games->count() }}</i> {{ plural($games->count(), $lang['plural_game']), $current_locale->id }}</h3>
                <div class="thumbs">
                        @foreach($games as $game)
                            <div class="thumb">
                                <div class="thumb-inner">
                                    <div class="thumb-link-wrap">
                                        @include('include.game_item_wrap', ['game' => $game])
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @if($games->total() > config('site.page.per_page'))

                        @if($games->currentPage() != $games->lastPage() && $games->total() > 0)
                            <div style="margin: 5px auto">
                                <button type="button" data-page="{{ $games->currentPage() }}" class="primary-btn loadmore">
                                    {{ $lang['show_more'] }} {{ ($games->currentPage() + 1 == $games->lastPage() ? $games->total() - (config('site.page.per_page')*$games->currentPage()) : config('site.page.per_page')) }} {{ $lang['game_more'] }}
                                </button>
                            </div>

                        @endif
                    @endif
{{--                    </div>--}}
{{--                        @if($games->lastPage() > 1)--}}
{{--                            {{ $games->links('component.pagination') }}--}}
{{--                        @endif--}}
{{--                    </div>--}}
            @endif
        </div>
    </section>
@endsection
@section('script')
<script type="application/javascript">

    var curentPage = parseInt($('button.loadmore').data('page'));
    var sectionGammes = $('.section-gammes-child');

    var loadingP = '<div class="text-center lading-sector">\n' +
        '                    <img src="https://img.gamesgo.net/images/unnamed.gif">\n' +
        '                </div>';

    $('button.loadmore').on('click', function () {
        curentPage++;
        searchLoadGame();
    });

    function searchLoadGame() {
        $.ajax({
            type: "POST",
            url: '{{ route('index') }}search/{{ $word }}',
            cache: false,
            data: {page: curentPage, type: 'search'},
            beforeSend: function() {
                // setting a timeout
                $('.thumbs').append(loadingP);
                $('button.loadmore').hide();
            },
            success: function(data)
            {
                if (data.success) {
                    $('.lading-sector').remove();
                    $('.thumbs').append(data.html);
                    //PageGame.loadMore();
                    $currentPage = data.current_page

                    var paginationWrapLink = $('.pagination-link[data-page="' + $currentPage + '"]')
                    if (paginationWrapLink.length) {
                        paginationWrapLink.addClass('is-active');
                        $('.pagination-link.is-active').each(function(){
                            $(this).replaceWith("<div class=\"pagination-link is-active\">" + $(this).html() + "</div>");
                        });
                    }

                    if(data.show_more == false) {
                        $('button.loadmore').hide();
                        if($('.pagination-item.is-last').length) {
                            $('.pagination-item.is-last').replaceWith('<li class="pagination-btn is-disabled is-last"><span class="icon-arrow-right-light"></span></li>');
                        }
                    }
                    showThumbPreview();
                    // itemWrapMouseenter();
                    lozad('.demilazyload').observe();
                }
            }
        });
    }
</script>
@endsection