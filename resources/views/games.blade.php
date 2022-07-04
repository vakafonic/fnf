@extends('layouts.app')

@if(request()->get('page') > 1 || $lang_page_best == true)
    {{--@section('seo_title', sprintf($lang['page_n_d'], request()->get('page')) . ' - ' . ($lang_page->seo_name ?? $lang_page->name))
    @section('seo_description', sprintf($lang['page_n_d'], request()->get('page')) . '. ' . ($lang_page->seo_description ?? $lang_page->description_top))--}}
    @section('seo_title',$lang_page->seo_name ?? $lang_page->name)
    @section('seo_description', $lang_page->seo_description ?? $lang_page->description_top)
@else
    @section('seo_title',$lang_page->seo_name ?? $lang_page->name)
    @section('seo_description', $lang_page->seo_description ?? $lang_page->description_top)
@endif
@section('content')

    <section id="box-section-games">
        <div class="container">
            <div class="breadcrumbs">
                <a class="breadcrumbs-btn" href="{{ route('index') }}">
                    <span class="icon-arrow-left"></span>
                    <span class="breadcrumbs-btn-text">{{ $lang['online_games'] }}</span>
                </a>
                <button class="breadcrumbs-opener" type="button">
                    <i class="icon-arrow-down"></i>
                </button>
                <div class="breadcrumbs-nav-wrap">
                    <nav>
                        <ol class="breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">
                            <li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a class="breadcrumbs-link" itemprop="item" href="{{ route('index') }}">
                                    <span itemprop="name">{{ $lang['online_games'] }}</span>
                                </a>
                                <meta itemprop="position" content="1" />
                            </li>
                            <li class="breadcrumbs-item">
                                <span class="breadcrumbs-link">{{ $lang_page->name }}</span>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="headline">
                <div class="headline-col" itemscope itemtype="http://schema.org/Game">
                    <h1 class="title-big">{{ $lang_page->name }}{{ request()->get('page') > 1 ? ' - ' . sprintf($lang['page_n_d'], request()->get('page')) : '' }}</h1>
                    @include('component.rating', ['url' => 'page', 'page' => $page])
                </div>
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
            </div>
            @if(!request()->get('page'))
                <div class="section-desc">
                    <p>{!! $lang_page->description_top ?? $lang_page->description !!}</p>
                </div>
            @endif
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
            @if(!request()->get('page'))
                <div class="description">
                    <h3>{{ $lang_page->h2 }}</h3>
                    <p>{!! $lang_page->description_buttom !!}</p>
                </div>
            @endif
        </div>
    </section>
@endsection
@section('script')
<script type="application/javascript">

    var $sectionGammes = $('#box-section-games');

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
        },
        loadGame: function (page, full) {
            $.ajax({
                type: "POST",
                url: '{{ route('index') }}postgames/{!! $url !!}/{!! $best ? 1 : 0 !!}/' + full + '/{{ $page->genre_id ?? $page->id }}?page=' + page,
                cache: false,
                beforeSend: function() {
                    // setting a timeout
                    if($sectionGammes.find('.thumbs').length) {
                        $sectionGammes.find('.thumbs').append(loadingP);
                    } else {
                        $sectionGammes.append(loadingP);
                    }
                },
                success: function(data)
                {
                    if (data.success) {
                        $('.lading-sector').remove();
                        if(full) {
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

                        $('.loadmore').find('span').text(data.show_number);
        
                        if(data.show_more == false) {
                            $('.thumbs + .primary-btn').remove();
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
