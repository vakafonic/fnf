@extends('layouts.app')
@if($best)
@section('canonical')
    <link rel="canonical" href="{{ route('seo.url', ['slug' => $url_many]) }}"/>
@endsection
@if(request()->get('page') > 1)
    @if($lang_page->h1)
        @section('seo_title', app()->getLocale() == 'ru' ? "Лучшие $lang_page->h1 страница № - ". request()->get('page') : "Best $lang_page->h1 page № - ". request()->get('page'))
@section('seo_description', app()->getLocale() == 'ru' ? "Лучшие $lang_page->h1 страница №".request()->get('page').". Играйте на GamesGo." : "Best $lang_page->h1 page №".request()->get('page').". Play on GamesGo.")
@else
    @section('seo_title', app()->getLocale() == 'ru' ? "Лучшие $lang_page->name страница № - ". request()->get('page') : "Best $lang_page->name page № - ". request()->get('page'))
@section('seo_description', app()->getLocale() == 'ru' ? "Лучшие $lang_page->name страница №".request()->get('page').". Играйте на GamesGo." : "Best $lang_page->name page №".request()->get('page').". Play on GamesGo.")
@endif
@else
    @section('seo_title', $lang_page->seo_title ?? $lang_page->seo_name ?? $lang_page->h1 ?? $lang_page->name)
@section('seo_description', $lang_page->seo_description ?? $lang_page->description_top)
@endif
@else
    @if(request()->get('page') > 1)
        @if($lang_page->h1)
            @section('seo_title', app()->getLocale() == 'ru' ? "$lang_page->h1 страница № - ". request()->get('page') : "$lang_page->h1 page № - ". request()->get('page'))
@section('seo_description', app()->getLocale() == 'ru' ? "$lang_page->h1 страница №".request()->get('page').". Играйте на GamesGo." : "$lang_page->h1 page №".request()->get('page').". Play on GamesGo.")
@else
    @section('seo_title', app()->getLocale() == 'ru' ? "$lang_page->name страница № - ". request()->get('page') : "$lang_page->name page № - ". request()->get('page'))
@section('seo_description', app()->getLocale() == 'ru' ? "$lang_page->name страница №".request()->get('page').". Играйте на GamesGo." : "$lang_page->name page №".request()->get('page').". Play on GamesGo.")
@endif
@else
    @section('seo_title', $lang_page->seo_title ?? $lang_page->seo_name ?? $lang_page->h1 ?? $lang_page->name)
@section('seo_description', $lang_page->seo_description ?? $lang_page->description_top)
@endif
@endif
@section('content')
    <section id="section-games">
        <div class="container">
            <div class="breadcrumbs">
                <a class="breadcrumbs-btn" href="{{ route('index') }}">
                    <span class="icon-arrow-left"></span>
                    <span class="breadcrumbs-btn-text">{{ $lang['online_games'] }}</span>
                </a>
                @if(count($relatedCategoriesBreadcrumbs) > 0)
                    <button class="breadcrumbs-opener" type="button">
                        <i class="icon-arrow-down"></i>
                    </button>
                @endif
                <div class="breadcrumbs-nav-wrap">
                    <nav>
                        <ol class="breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">
                            <li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a class="breadcrumbs-link" itemprop="item" href="{{ route('index') }}">
                                    <span itemprop="name">{{ $lang['online_games'] }}</span>
                                </a>
                                <meta itemprop="position" content="1" />
                            </li>
                            @if(count($relatedCategoriesBreadcrumbs) > 0)
                                @php $position = 2; @endphp
                                @foreach($relatedCategoriesBreadcrumbs as $categoryBreadcrumbs)
                                    @if(end($relatedCategoriesBreadcrumbs) != $categoryBreadcrumbs)
                                        <li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                            <a class="breadcrumbs-link" itemscope itemtype="https://schema.org/WebPage" itemprop="item" itemid="{{ route('seo.url', ['url' => $categoryBreadcrumbs['url']]) }}" href="{{ route('seo.url', ['url' => $categoryBreadcrumbs['url']]) }}">
                                                <span itemprop="name">{{ $categoryBreadcrumbs['value'] }}</span>
                                            </a>
                                            <meta itemprop="position" content="{{$position}}" />
                                        </li>
                                    @else
                                        <li class="breadcrumbs-item">
                                            <span class="breadcrumbs-link">{{ $categoryBreadcrumbs['value'] }}</span>
                                        </li>
                                    @endif
                                    @php $position++; @endphp
                                @endforeach
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="headline">
                <div class="headline-col" itemscope itemtype="http://schema.org/Game">
                    <h1 class="title-big">{{ $lang_page->h1 ?? $lang_page->name }}{{ request()->get('page') > 1 ? ' - ' . sprintf($lang['page_n_d'], request()->get('page')) : '' }}</h1>
                    @include('component.rating', ['url' => $url, 'page' => $page])
                </div>
                @if(!$skipBestTab)
                <div class="tabs">
                    @if(!$best)
                        <div class="tab-first {!! !$best ? 'is-current' : '' !!}"><span
                                    class="tab-text">{{ $lang['new'] }}</span></div>
                    @else
                        <a href="{{ route('games', ['url' => $url_many]) }}"
                           class="tab-first {!! !$best ? 'is-current' : '' !!}"><span
                                    class="tab-text">{{ $lang['new'] }}</span></a>
                    @endif
                    @if($best)
                        <div
                                class="tab-last {!! $best ? 'is-current' : '' !!}"><span
                                    class="tab-text">{{ $lang['best'] }}</span></div>
                    @else
                        <a href="{{ route('games', ['url' => 'best-' . $url_many]) }}"
                           class="tab-last {!! $best ? 'is-current' : '' !!}"><span
                                    class="tab-text">{{ $lang['best'] }}</span></a>
                    @endif
                </div>
                @endif
            </div>
            @if(!is_null($categories) && $categories->count() > 0)
                <div class="categories-carousel">
                    <div class="categories-swiper-container">
                        <div class="categories-swiper-wrapper">
                            @foreach($categories as $index => $genry)
                                @if($index == 4 && count($categories) > 5)
                                    <div class="category has-btn">
                                        <button class="show-more-btn" type="button"><span
                                                    class="show-more-text">{{ $lang['show_all'] }}</span></button>
                                    </div>
                                @endif
                                @include('include.genry_item_menu', compact('genry'))
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if($current_page == 1 && !$best || $isMainMenuPage && $current_page == 1)
                @if($lang_page->description_top || $lang_page->description)
                    <div class="section-desc">
                        <p>{!! $lang_page->description_top ?? $lang_page->description !!}</p>
                    </div>
                @endif
            @endif
            @if(!$skipBestTab)
            <div class="tabs">
                @if(!$best)
                    <div class="tab-first {!! !$best ? 'is-current' : '' !!}"><span
                                class="tab-text">{{ $lang['new'] }}</span></div>
                @else
                    <a href="{{ route('games', ['url' => $url_many]) }}" class="tab-first {!! !$best ? 'is-current' : '' !!}"><span class="tab-text">{{ $lang['new'] }}</span></a>
                @endif
                @if($best)
                    <div
                            class="tab-last {!! $best ? 'is-current' : '' !!}"><span
                                class="tab-text">{{ $lang['best'] }}</span></div>
                @else
                    <a href="{{ route('games', ['url' => 'best-' . $url_many]) }}" class="tab-last {!! $best ? 'is-current' : '' !!}"><span class="tab-text">{{ $lang['best'] }}</span></a>
                @endif
            </div>
            @endif
            {!! $htmlBlock['html'] ?? '' !!}
            @if(count($footerHeroes) > 0 || count($footerGenres) > 0)
                <div class="tags">
                    <div class="tags-inner">
                        <nav class="tags-nav">
                            <ul class="tags-list">
                                @foreach($footerHeroes as $key => $heroes)
                                    <li class="tags-item">
                                        <a class="tags-link" href="{{ route('seo.url', ['slug' => $heroes['url']]) }}">
                                            <span class="tags-text">{{ $heroes['value'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                                @foreach($footerGenres as $key => $genres)
                                    <li class="tags-item">
                                        <a class="tags-link" href="{{ route('seo.url', ['slug' => $genres['url']]) }}">
                                            <span class="tags-text">{{ $genres['value'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                </div>
            @endif
            @if($current_page == 1)
                <br>
                <div class="description">
                    @if($lang_page->h2)
                        <h2>{{ $lang_page->h2 }}</h2>
                    @endif
                    <p>{!! $lang_page->description_buttom !!}</p>
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script type="application/javascript">

        var $sectionGammes = $('#section-games');

        var $currentPage = {!! $current_page !!};

        var loadingP = '<div class="text-center lading-sector">\n' +
            '                    <img src="https://img.gamesgo.net/images/unnamed.gif">\n' +
            '                </div>';

        var PageGame = {
            init: function () {
                // PageGame.loadGame({!! $current_page !!}, 1);
                // itemWrapMouseenter();
                // showThumbPreviewByHover();
                PageGame.loadMore();
                this.prepareBests()
            },
            prepareBests: () => {
                if (window.screen.width <= 991) {
                    let len = document.getElementsByClassName("best-games").length

                    for (let i = 0; i <= len; i++) {
                        if (document.getElementsByClassName("best-games").length <= 14) break
                        document.getElementsByClassName("best-games")[document.getElementsByClassName("best-games").length - 1].remove()
                    }
                }
            },
            loadGame: function (page, full) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('index') }}games/{!! $url !!}/{!! $slug !!}/{!! $best ? 1 : 0 !!}/' + full + '/{{ $page->id }}?page=' + page,
                    cache: false,
                    beforeSend: function () {
                        // setting a timeout
                        if ($sectionGammes.find('.thumbs').length) {
                            $sectionGammes.find('.thumbs').append(loadingP);
                        } else {
                            $sectionGammes.append(loadingP);
                        }
                    },
                    success: function (data) {
                        if (data.success) {
                            $('.lading-sector').remove();
                            if (full) {
                                $sectionGammes.html(data.html);
                                PageGame.loadMore();
                            } else {
                                $sectionGammes.find('.thumbs').append(data.html);
                            }
                            //PageGame.loadMore();
                            $currentPage = data.current_page

                            var paginationWrapLink = $('.pagination-link[data-page="' + $currentPage + '"]')

                            if (paginationWrapLink.length) {
                                paginationWrapLink.addClass('is-active');
                                $('.pagination-link.is-active').each(function(){
                                    $(this).replaceWith("<div class=\"pagination-link is-active\">" + $(this).html() + "</div>");
                                });
                            }

                            $('.loadmore').find('span').text(data.show_number + ' ' + (data.show_number > 4 ? '{{ $lang['game_more'] }}' : '{{ $lang['game_mores'] }}'));

                            if (data.show_more == false) {
                                $('.thumbs + .primary-btn').remove();
                                if ($('.pagination-item.is-last').length) {
                                    $('.pagination-item.is-last').replaceWith('<li class="pagination-btn is-disabled is-last"><span class="icon-arrow-right-light"></span></li>');
                                }
                            }

                            // itemWrapMouseenter();
                            showThumbPreview();
                            lozad('.demilazyload').observe();
                        }
                    }
                });
            },
            loadMore: function () {
                $('button.primary-btn').on('click', function () {
                    PageGame.loadGame(parseInt($currentPage) + 1, 0);
                });
            }
        };

        $(function () {
            'use strict';
            //jQuery code here
            PageGame.init();
        });
    </script>
@endsection
