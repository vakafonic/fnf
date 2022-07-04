@extends('layouts.app')
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
            <div class="found">
                <div class="search-no">{{ $lang['nothing_found'] }}</div>
                <h1 class="title-big">{{ $lang['upon_request'] }} «<span class="search-target">{{ $word }}</span>»</h1>
                <div class="search-empty">{{ $lang['try_changing_query'] }}</div>

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
        @php
        $gamesController = new App\Http\Controllers\GameController;
        $bestGames = $gamesController->modelGames(true, 10);
        @endphp
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

            @if($bestGames->currentPage() != $bestGames->lastPage() && $bestGames->total() > 0)
                <button class="primary-btn" type="button" data-page="{{ $bestGames->currentPage() }}" id="loadBestGames">
                    {{ $lang['show_more'] }}&nbsp;
                    <span>{{ $totalCurrentPage = ($bestGames->currentPage() + 1 == $bestGames->lastPage() ? $bestGames->total() - (config('site.page.per_page')*$bestGames->currentPage()) : config('site.page.per_page')) }}&nbsp;{{ plural($totalCurrentPage, $lang['plural_game'], $this->current_locale->id ?? 1) }}
                </span>
                </button>
            @endif
        </div>
    </section>
@endsection
@section('script')
    <script type="application/javascript">

        var loadingP = '<div style="text-align: center" class="text-center lading-sector">\n' +
            '                    <img src="https://img.gamesgo.net/images/unnamed.gif">\n' +
            '                </div>';
        var $loadmore = $('.loadmore');

        var $pageGameSectionGammes = $('#gamesBox');
        var $pageOnlineGameSection = $('#onlineGames');

        var $currentBestPage = 0;
        var $currentPage = 1;

        var PageGame = {
            init: function () {
                // itemWrapMouseenter();
                // showThumbPreviewByHover();
                PageGame.loadMoreBestGame();
                PageGame.loadMoreGame();
            },
            loadBestGame: function (page, full) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('index') }}postgames/best-games/1/' + full + '/3?page=' + page,
                    cache: false,
                    data: {loadmoreBests: true},
                    beforeSend: function() {
                        // setting a timeout
                        if($pageGameSectionGammes.find('.thumbs').length) {
                            $pageGameSectionGammes.find('.thumbs').append(loadingP);
                        } else {
                            $pageGameSectionGammes.append(loadingP);
                        }
                    },
                    success: function(data)
                    {
                        if (data.success) {
                            $('.lading-sector').remove();
                            if(full) {
                                $pageGameSectionGammes.html(data.html);
                                PageGame.loadMore();
                            } else {
                                // console.log($pageGameSectionGammes.find('.thumbs'));
                                $('.thumbs').append(data.html);
                            }
                            //PageGame.loadMore();
                            $currentBestPage = data.current_page

                            var paginationWrapLink = $('.pagination-link[data-page="' + $currentPage + '"]')
                            if (paginationWrapLink.length) {
                                paginationWrapLink.addClass('is-active');
                                $('.pagination-link.is-active').each(function(){
                                    $(this).replaceWith("<div class=\"pagination-link is-active\">" + $(this).html() + "</div>");
                                });
                            }

                            $('#loadBestGames').find('span').text(data.show_number);
                            if(data.show_more == false) {
                                $('#loadBestGames').remove();
                                if($('.pagination-item.is-last').length) {
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
            loadGame: function (page, full) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('index') }}postgames/games/0/' + full + '/2?page=' + page,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        if($pageOnlineGameSection.find('.thumbs').length) {
                            $pageOnlineGameSection.find('.thumbs').append(loadingP);
                        } else {
                            $pageOnlineGameSection.append(loadingP);
                        }
                    },
                    success: function(data)
                    {
                        if (data.success) {
                            $('.lading-sector').remove();
                            if(full) {
                                $pageOnlineGameSection.html(data.html);
                                PageGame.loadMore();
                            } else {
                                $pageOnlineGameSection.find('.thumbs').append(data.html);
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

                            $('#loadmoreGame').find('span').text(data.show_number);
                            if(data.show_more == false) {
                                $('#loadmoreGame').remove();
                                if($('.pagination-item.is-last').length) {
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

            loadMoreBestGame: function () {
                $('#loadBestGames').on('click', function () {
                    PageGame.loadBestGame(parseInt($currentBestPage) + 1, 0);
                });
            },
            loadMoreGame: function () {
                $('#loadmoreGame').on('click', function () {
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