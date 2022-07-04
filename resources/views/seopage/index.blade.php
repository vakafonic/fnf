@extends('layouts.app')
@section('seo_title', $seopage_lang->seo_title ?? $seopage_lang->value)
@section('seo_description', $seopage_lang->seo_description ?? $seopage_lang->description)
@section('content')
    <div class="container">
        <div class="path">
            <div class="path__back">{{ $lang['back'] }}</div>
            {{--<div class="path__down"></div>--}}
            <a class="path__home" href="{{ route('index') }}">{{ $lang['home'] }}</a>
            <span>—</span>
            {{ $seopage_lang->value }}
        </div>

        <div class="underpath">

            <div class="title">
                <h1 class="title__h1">{{ $seopage_lang->value }}</h1>
            </div>

            <div class="description">
                <p>{!! $seopage_lang->description_top ?? $seopage_lang->description !!}</p>
            </div>

            <div class="section-gammes">
                {{--load via ajax--}}
            </div>

            <div class="description">
                <h2>{{ $seopage_lang->h2 }}</h2>
                <p>{!! $seopage_lang->description_buttom !!}</p>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script type="application/javascript">
        var $sectionGammes = $('.section-gammes');

        var $currentPage = 1;

        var loadingP = '<div class="text-center lading-sector">\n' +
            '                    <img src="https://img.gamesgo.net/images/unnamed.gif">\n' +
            '                </div>';

        var PageGame = {
            init: function () {
                PageGame.loadGame({!! $current_page !!}, 1);
            },
            loadGame: function (page, full) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('index') }}multi/' + full + '/{{ $seopage->id }}?page=' + page,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        if($sectionGammes.find('.items').length) {
                            $sectionGammes.find('.items').append(loadingP);
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
                                $sectionGammes.find('.items').append(data.html);
                            }
                            //PageGame.loadMore();
                            $currentPage = data.current_page

                            var paginationWrapLink = $('.pagination__wrap').find('a[data-page="' + $currentPage + '"]');
                            if (paginationWrapLink.length) {
                                paginationWrapLink.addClass('isActive');
                            }

                            itemWrapMouseenter();

                            lozad('.demilazyload').observe();
                        }
                    }
                });
            },
            loadMore: function () {
                $('button.loadmore').on('click', function () {
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