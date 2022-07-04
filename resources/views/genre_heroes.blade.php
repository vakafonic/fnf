@extends('layouts.app')
@section('seo_title', $lang_page->seo_title ?? $lang_page->seo_name ?? $lang_page->h1 ?? $lang_page->name)
@section('seo_description', $lang_page->seo_description ?? $lang_page->description_top)
@section('content')

    <section>
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
                                <span class="breadcrumbs-link">{{ $lang_page->value ?? $lang_page->name }}</span>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="headline">
                <div class="headline-col" itemscope itemtype="http://schema.org/Game">
                    <h1 class="title-big">{{ $lang_page->h1 ?? $lang_page->name }}</h1>
                    @include('component.rating', ['url' => 'page', 'page' => $page])
                </div>
            </div>
            @if($lang_page->description_top ?? $lang_page->description)
                <div class="description">
                    <p>{!! $lang_page->description_top ?? $lang_page->description !!}</p>
                </div>
            @endif
            <br>
            <div class="categories-secondary">
                {!! $html ?? '' !!}
            </div>
{{--            <button style="@if(!$show_more) display: none @endif" type="button" class="primary-btn loadmore">{{ $lang['show_more_more'] }}</button>--}}
            <div class="description">
                @if($lang_page->h2)
                    <h2>{{ $lang_page->h2 }}</h2>
                @endif
                <p>{!! $lang_page->description_buttom !!}</p>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="application/javascript">

        var $sectionGammes = $('.categories-secondary');

        var $currentPage = 2;

        var $loadmore = $('.loadmore');

        var loadingP = '<div class="text-center lading-sector">\n' +
            '                    <img src="https://img.gamesgo.net/images/unnamed.gif">\n' +
            '                </div>';

        var PageGenreHeroes = {
            init: function () {
                //PageGenreHeroes.loadGam();

                checkCatsNameWidth();

                lozad('.demilazyload').observe();

                $loadmore.on('click', function () {
                    PageGenreHeroes.loadGam();
                });
            },
            loadGam: function () {
                $.ajax({
                    type: "POST",
                    url: '{{ Request::url() }}?page=' + $currentPage,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        $loadmore.hide();
                        $sectionGammes.append(loadingP);
                    },
                    success: function(data)
                    {
                        if (data.success) {
                            $('.lading-sector').remove();
                            $sectionGammes.append(data.html);
                            $currentPage = parseInt(data.current_page ) + 1;

                            if (data.show_more === true) {
                                $loadmore.show('slow');
                            }

                            checkCatsNameWidth();

                            lozad('.demilazyload').observe();
                        }
                    }
                });
            }
        };

        $(function () {
            'use strict';
            //jQuery code here
            PageGenreHeroes.init();
        });

        function checkCatsNameWidth() {
            $('.cat__name span').each(function(i, el) {
                $(this).removeClass('isOneline');
                var lineHeight = parseInt($(this).css('line-height').replace('px', ''));
                if ($(this).innerHeight() <= lineHeight) {
                    $(this).addClass('isOneline');
                }
            });
        }
    </script>
@endsection
