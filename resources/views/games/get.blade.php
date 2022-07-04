@extends('layouts.app')
@section('seo_title', $game_lang->seo_name ?? sprintf($lang['game_s_play_online'], $game_lang->name))
@section('seo_description', $game_lang->seo_description ?? sprintf($lang['play_s_free_desc'], $game_lang->name, config('app.name', 'GamesGo'), $game_lang->name ))
@section('stylesheet')
    <meta property="og:site_name" content="{{ config('app.name', 'GamesGo') }}"/>
    <meta property="og:title"
          content="{{ $game_lang->seo_name ?? sprintf($lang['game_s_play_online'], $game_lang->name) }}"/>
    <meta property="og:description"
          content="{{ $game_lang->seo_description ?? sprintf($lang['play_s_free_desc'], $game_lang->name, config('app.name', 'GamesGo'), $game_lang->name ) }}"/>
    <meta property="og:type" content="Game"/>
    <meta property="og:url" content="{{ route('seo.url', ['url' => $game->url]) }}"/>
    <meta property="og:image" content="{{ $game->getMainImage() }}"/>
@endsection
@section('content')
    <section itemscope="" itemtype="http://schema.org/Game">
        <div>
            <div class="game">
                <svg class="game-bg">
                    <defs>
                        <filter id="clip-blur">
                            <fegaussianblur stddeviation="25" color-interpolation-filters="sRGB"></fegaussianblur>
                        </filter>
                    </defs>
                    <image xlink:href="{{ $game->getMainImage() }}" x="0" y="0" width="100%" height="100%"
                           preserveaspectratio="xMidYMid slice" filter="url(#clip-blur)"></image>
                </svg>
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
                                            <li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                                <a class="breadcrumbs-link" itemscope itemtype="https://schema.org/WebPage" itemprop="item" itemid="{{ route('seo.url', ['url' => $categoryBreadcrumbs['url']]) }}" href="{{ route('seo.url', ['url' => $categoryBreadcrumbs['url']]) }}">
                                                    <span itemprop="name">{{ $categoryBreadcrumbs['value'] }}</span>
                                                </a>
                                                <meta itemprop="position" content="{{$position}}" />
                                            </li>
                                            @php $position++; @endphp
                                        @endforeach
                                    @endif
                                </ol>
                            </nav>
                        </div>
                        @foreach($game->getGenresOfGames($current_locale->id) as $genry)
                            <meta itemprop="genre" content="{{ route('seo.url', ['slug' => $genry->url]) }}"/>
                        @endforeach
                    </div>
                    <div class="game-headline">
                        <hgroup class="game-hgroup">
                            <h1 class="title-big" itemprop="name">{{ $game_lang->name }} </h1>
                            <meta itemprop="thumbnailUrl" content="{{ $game->getMainImage() }}"/>
                            @if(LaravelLocalization::getCurrentLocale() == 'ru' || LaravelLocalization::getCurrentLocale() == 'uk')
                                <h3 class="subtitle">{{ $game->getSubTitle() }}</h3>
                            @endif
                        </hgroup>
                        @if (($game->mobi && (!isIphoneDevice() || (isIphoneDevice() && $game->iphone == 0))) || !isMobileDevice())
                            <div class="game-review">
                                @php
                                    $count_comment = $game->comments()->whereConfirmed(1)->count()
                                @endphp
                                <div class="game-review-col" itemprop="interactionStatistic" itemscope
                                     itemtype="http://schema.org/InteractionCounter">
                                    <meta itemprop="userInteractionCount"
                                          content="{{ $game->comments()->whereConfirmed(1)->count()  }}"/>
                                    <meta itemprop="interactionType" content="http://schema.org/CommentAction"/>
                                    <button class="game-review-link" type="button" data-toggler="feedback">
                                        @if($count_comment > 0){{ $count_comment }} {{ plural($count_comment, $lang['plural_review'], $current_locale->id ?? 1) }}
                                        @else{{ $lang['give_feedback'] }}
                                        @endif
                                    </button>
                                    {{ $game->unic->count() ?? 1 }} {{ plural($game->unic->count() ?? 1, $lang['player_players'], $current_locale->id) }}
                                </div>
                                <div class="game-review-col-last" @if($game->rating > 0) itemprop="aggregateRating"
                                     itemscope="" itemtype="http://schema.org/AggregateRating">
                                    <meta itemprop="ratingValue" content="{{ round($game->rating/20, 1) }}"/>
                                    <meta itemprop="reviewCount" content="{{ $comments->total }}"/>
                                    <meta itemprop="ratingCount"
                                          content="{{ $game->game_likes + $game->game_dislikes }}"/>@else > @endif

                                    <button class="game-review-btn game-review-like @if($game->checkUserLike()) isActive @endif"
                                            onclick="GamePage.like(this, 1);" type="button">
                                        <i class="icon-like"></i>
                                        <span class="game-review-num">@if($game->game_likes > 0){{ $game->game_likes }}@endif</span>
                                    </button>
                                    <button class="game-review-btn game-review-dislike @if($game->checkUserDislike()) isActive @endif"
                                            onclick="GamePage.like(this, 0);" type="button">
                                        <i class="icon-dislike"></i>
                                        <span class="game-review-num">@if($game->game_dislikes > 0){{ $game->game_dislikes }}@endif</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    {{--                <div id="boxShare" style="display:none; margin-top: -58px;">--}}
                    {{--                    <!-- uSocial -->--}}
                    {{--                    <script async src="https://usocial.pro/usocial/usocial.js?uid=5492e03cd7f60166&v=6.1.5" data-script="usocial" charset="utf-8"></script>--}}
                    {{--                    <div class="uSocial-Share" data-pid="88a8bec0182f5108998252a34eaadc7b" data-type="share" data-options="round,style1,default,absolute,horizontal,size48,counter0,mobile_position_right" data-social="telegram,vi,twi,fb,vk,ok,wa"></div>--}}
                    {{--                    <!-- /uSocial -->--}}
                    {{--                </div>--}}
                    @if (($game->mobi && (!isIphoneDevice() || (isIphoneDevice() && $game->iphone == 0))) || !isMobileDevice())
                        <div class="game-btns">
                            <button class="game-btn {{$game->checkFavorite() ? 'isActive' : ''}}" type="button"
                                    onclick="GamePage.favorite(this)">
                                <i class="icon-star"></i>
                                @if($game->checkFavorite())
                                    <span class="game-btn-text">{{ $lang['in_favorites'] }}</span>
                                @else
                                    <span class="game-btn-text">{{ $lang['to_favorites'] }}</span>
                                @endif
                            </button>
                            <button class="game-btn js-go-feed is-hidden" type="button">
                                <i class="icon-speech-bubble"></i>
                            </button>
                            <button class="game-btn has-no-border js-not-working" type="button">
                                <i class="icon-warning"></i>
                                <span class="game-btn-text">{{ $lang['problem_game'] }}</span>
                            </button>
                            <div class="shares">
                                <button class="game-btn shares-btn" onclick="GamePage.shareMob()" data-close="false"
                                        id="buttonShare" type="button">
                                    <i class="icon-share-social"></i>
                                    <i class="icon-share"></i>
                                    <span class="game-btn-text">{{$lang['share']}}</span>
                                </button>
                                <ul class="shares-list">
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/telegram.svg')}}"
                                                                                                  alt="telegram"></a>
                                    </li>
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/viber.svg')}}"
                                                                                                  alt="viber"></a></li>
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/twitter.svg')}}"
                                                                                                  alt="twitter"></a>
                                    </li>
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/facebook.svg')}}"
                                                                                                  alt="facebook"></a>
                                    </li>
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/vk.svg')}}"
                                                                                                  alt="vk"></a></li>
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/instagram.svg')}}"
                                                                                                  alt="instagram"></a>
                                    </li>
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/messenger.svg')}}"
                                                                                                  alt="messenger"></a>
                                    </li>
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/odnoklassniki.svg')}}"
                                                                                                  alt="odnoklassniki"></a>
                                    </li>
                                    <li class="shares-item"><a class="shares-link" href="#"> <img class="shares-img"
                                                                                                  src="{{asset('svg/shares/whatsapp.svg')}}"
                                                                                                  alt="whatsapp"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif

                    @php($notMobGame = !(($game->mobi && (!isIphoneDevice() || (isIphoneDevice() && $game->iphone == 0))) || !isMobileDevice()))
                    <div class="start">
                        <div class="start-first-col">
                            <div class="game-img-wrap">
                                <img alt="{{ $game_lang->name }}" src="{{ $game->getMainImage() }}"
                                     data-srcset="{{ $game->getMainImage() }}"
                                     srcset="/images/watermark_300x300.png"
                                     class="@if(!$notMobGame) js-start-game @endif demilazyload" data-fullscreen="true"
                                     data-target-blank="{{ $game->target_blank ? 1 : 0 }}"
                                     data-iframe-width="{{ !empty($game->width) ? (int)$game->width : ($game->size_width == 1 ? '100%' : '380') }}"
                                     data-iframe-height="{{ !empty($game->height) ? (int)$game->height : '600' }}"
                                     data-iframe-src="{{ $game->iframe_url }}"
                                     data-iframe-styles="{{ $game->calculateIframeStyles() }}"
                                     @if($notMobGame) style="animation: none;" @endif>
                            </div>
                        </div>
                        <div class="start-last-col">
                            @if (($game->mobi && (!isIphoneDevice() || (isIphoneDevice() && $game->iphone == 0))) || !isMobileDevice())
                                <button id="jsstartgame" alt="{{ $game_lang->name }}" class="primary-btn js-start-game"
                                        data-sandbox="{{ $game->sandbox }}"
                                        data-target-blank="{{ $game->target_blank ? 1 : 0 }}" data-fullscreen="true"
                                        data-iframe-width="{{ !empty($game->width) ? (int)$game->width : ($game->size_width == 1 ? '100%' : '380') }}"
                                        data-iframe-height="{{ !empty($game->height) ? (int)$game->height : '600' }}"
                                        data-iframe-src="{{ $game->iframe_url }}"
                                        data-iframe-styles="{{ $game->calculateIframeStyles() }}"
                                        data-horizontal="{{$game->horizontal}}" type="button">
                                    {{ $lang['play'] }}
                                </button>
                                <div class="start-btns">
                                    <div class="start-btn-wrap">
                                        <button class="start-btn start-btn-like @if($game->checkUserLike()) isActive @endif"
                                                type="button"
                                                onclick="GamePage.like(this, 1);">
                                            <i class="icon-like"></i>
                                            <span class="start-btn-text">@if($game->game_likes > 0){{ $game->game_likes }}@endif</span></button>
                                    </div>
                                    <div class="start-btn-wrap">
                                        <button class="start-btn start-btn-dislike @if($game->checkUserDislike()) isActive @endif"
                                                type="button" onclick="GamePage.like(this, 0);">
                                            <i class="icon-dislike"></i>
                                            <span class="start-btn-text">@if($game->game_dislikes > 0){{ $game->game_dislikes }}@endif</span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="start-last-col-text">{{$lang['only_computer']}}</div>
                                <button id="scrollToSames" alt="{{$lang['play_similar']}}" class="primary-btn"
                                        type="button">{{$lang['play_similar']}}</button>
                            @endif
                            {{-- <br> --}}
                        </div>
                    </div>


                                    <div class="game-carousel">

                                    </div>
                </div>
                <div class="container">
                    <div class="game-frame">
                        @if($game->no_block_ad && !isMobileDevice())
                            <div class="adblocker-line">
                                <div class="adblocker-notify game__blocker">{{ $lang['disable_adblock_start_game'] }}</div>
                            </div>
                        @endif
                        <div class="iframe-buttons">
                            <button class="game-closer js-go-fullscreen" type="button"><i class="icon-scale-arrows"></i></button>
                            <button class="game-reloader js-refresh-iframe"><i class="icon-reload"></i></button>
                        </div>
                        <div class="game-frame-box" style="display:none; overflow: hidden">

                        </div>
                        <div class="game-frame-inner" itemprop="potentialAction" itemscope
                             itemtype="http://schema.org/WatchAction">
                            <meta itemprop="target" content="{{ $game->iframe_url }}"/>
                            <div class="game-img-wrap">
                                @if (($game->mobi && (!isIphoneDevice() || (isIphoneDevice() && $game->iphone == 0))) || !isMobileDevice())
                                    <img itemprop="image"
                                         alt="{{ $game_lang->name }}"
                                         src="{{ $game->getMainImage() }}"
                                         data-srcset="{{ $game->getMainImage() }} 100w"
                                         sizes="100vw"
                                         srcset="/images/watermark_300x300.png"
                                         class="js-start-game demilazyload" data-fullscreen="true"
                                         data-target-blank="{{ $game->target_blank ? 1 : 0 }}"
                                         data-iframe-width="{{ !empty($game->width) ? (int)$game->width : ($game->size_width == 1 ? '100%' : '380') }}"
                                         data-iframe-height="{{ !empty($game->height) ? (int)$game->height : '600' }}"
                                         data-iframe-src="{{ $game->iframe_url }}"
                                         data-iframe-styles="{{ $game->calculateIframeStyles() }}"
                                @else
                                    <img alt="{{ $game_lang->name }}" src="{{ $game->getMainImage() }}"
                                         data-srcset="{{ $game->getMainImage() }}"
                                         srcset="/images/watermark_300x300.png"
                                         class="demilazyload">
                                @endif
                            </div>

                        </div>
                        @if (($game->mobi && (!isIphoneDevice() || (isIphoneDevice() && $game->iphone == 0))) || !isMobileDevice())
                            <button id="jsstartgame" alt="{{ $game_lang->name }}" class="primary-btn js-start-game"
                                    data-sandbox="{{ $game->sandbox }}"
                                    data-target-blank="{{ $game->target_blank ? 1 : 0 }}" data-fullscreen="true"
                                    data-iframe-width="{{ !empty($game->width) ? (int)$game->width : ($game->size_width == 1 ? '100%' : '380') }}"
                                    data-iframe-height="{{ !empty($game->height) ? (int)$game->height : '600' }}"
                                    data-iframe-src="{{ $game->iframe_url }}"
                                    data-iframe-styles="{{ $game->calculateIframeStyles() }}"
                                    data-horizontal="{{$game->horizontal}}" type="button">
                                {{ $lang['play'] }}
                            </button>
                        @else
                            <div class="computers-only">{{$lang['only_computer']}}</div>
                            <button id="scrollToSames" alt="{{$lang['play_similar']}}" class="primary-btn"
                                    type="button">{{$lang['play_similar']}}</button>
                        @endif
                    </div>
                </div>
                <div class="instruction">
                    @if(is_array($comands) && count($comands) > 0)
                        <div class="title-small">{{ $lang['game_controls'] }}</div>
                        <div class="instruction-inner">
                            @foreach($comands as $player => $comandsp)
                                <div class="instruction-row">
                                    @if(count($comands) > 1)
                                        <div class="instruction-gamer">{{ $lang['player'] }} {{ $player }}</div>
                                    @endif
                                    <div class="instruction-col">
                                        @foreach($comandsp as $comand)
                                            <div class="instruction-info-wrap">
                                                <div class="instruction-info">
                                                    @if(isset($comand['new-name']) && is_array($comand['new-name']) && !empty($comand['new-name'][0]))
                                                        @if(isset($comand['new-name'][$current_locale->id]))
                                                            <div class="instruction-text">
                                                                {{ $comand['new-name'][$current_locale->id] }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if(!empty($comand['textarea']) && isset($comand['textarea'][$current_locale->id]) && $comand['textarea'][$current_locale->id] != "null")
                                                            @if(isset($comand['textarea'][$current_locale->id]))
                                                                <div class="instruction-text">
                                                                    {{ $comand['textarea'][$current_locale->id] }}
                                                                </div>
                                                            @endif
                                                        @elseif(!empty($comand['input']))
                                                            <div class="instruction-text">{{ \App\Models\ButtonsPlay::find($comand['input'][0])->getName($current_locale->id) }}</div>
                                                        @endif
                                                    @endif
                                                    @if(!empty($comand['input']))
                                                        @foreach($comand['input'] as $input)
                                                            @if($input == 71)
                                                                <div class="instruction-text">{{ $lang['or'] }}</div>
                                                            @else
                                                                @if($input == 67 && app()->getLocale() != 'ru')
                                                                    <div class="instruction-img-wrap"><img
                                                                                src="{{ Storage::disk('app')->url('icon/spaceEN.svg')}}"
                                                                                data-srcset="{{ Storage::disk('app')->url('icon/spaceEN.svg')}}"
                                                                                alt="mouse-all"></div>
                                                                @else
                                                                    <div class="instruction-img-wrap"><img
                                                                                src="https://img.gamesgo.net/icon/{{ \App\Models\ButtonsPlay::find($input)->icon }}"
                                                                                alt="mouse-all"></div>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="is-raised">
            <div class="game-tabs-container">
                <div class="game-tabs">
                    <button class="game-tab game-tab-all is-current"
                            type="button">{{ $lang['all_about_game'] }}</button>
{{--                                    <button class="game-tab" type="button" data-toggler="walkthrough">{{ $lang['video_walkthrough'] }}</button>--}}
                    @if(!empty($similarGames))
                        <button class="game-tab" type="button"
                                data-toggler="series">{{ $lang['games_from_this_series'] }}</button>
                    @endif
                    <button class="game-tab" type="button" data-toggler="feedback">{{ $lang['player_reviews_tips'] }}
                        <span>{!!  $comments->total > 0 ? '(' . $comments->total . ')' : '' !!}</span></button>
                </div>
            </div>
            <div class="game-toggle-bl">
                <div class="categories-container">
                    <div class="categories-wrapper">
                        {!! $genresAboutGame !!}
                    </div>
                </div>
            </div>
            <div class="game-toggle-row">
                <div class="game-toggle-col">
                    <div class="game-toggle-bl is-modified">
                        <div class="container">
                            <div class="about">
                                <div class="about-headline">
                                    <div class="about-img-wrap">
                                        <img class="about-img" src="https://img.gamesgo.net/images/about.svg"
                                             alt="placeholder"></div>
                                    <h3 class="about-title"> {{ $lang['description_purpose_game'] }}</h3>
                                </div>
                                <p class="about-text" itemprop="description">{!! nl2br($game_lang->description) !!}</p>
                            </div>
                            <div class="about">
                                <div class="about-headline">
                                    <div class="about-img-wrap"><img class="about-img"
                                                                     src="https://img.gamesgo.net/images/console-fill.svg"
                                                                     alt="placeholder"></div>
                                    <h3 class="about-title"> {{ $lang['how_to_play'] }}</h3>
                                </div>
                                <p class="about-text">{!! nl2br($game_lang->how_play) !!}</p>
                            </div>
                        </div>
                    </div>


{{--                                    <div class="game-toggle-bl" data-toggle="walkthrough">--}}
{{--                                        <div class="container">--}}
{{--                                            <div class="about is-modified">--}}
{{--                                                <div class="about-headline">--}}
{{--                                                    <div class="about-img-wrap">--}}
{{--                                                        <img class="about-img" src="https://img.gamesgo.net/images/yt.svg" alt="placeholder">--}}
{{--                                                    </div>--}}
{{--                                                    <h3 class="about-title">{{ $lang['video_walkthrough'] }}</h3>--}}
{{--                                                </div>--}}
{{--                                                 <iframe--}}
{{--                                                            scrolling="no"--}}
{{--                                                            frameborder="0"--}}
{{--                                                            allowfullscreen="allowfullscreen"--}}
{{--                                                            style="margin: 0px; padding: 0px;" width="640" height="480"--}}
{{--                                                            src="https://player.tubia.com/?publisherid=c6d6e59ddd58442ea2e3fadea03fcc10&gameid={{$game->id}}&pageurl={{URL::current()}}&title={{urlencode($game_lang->name)}}&colormain=ea8d0e&coloraccent=ea8d0e">--}}
{{--                                                        </iframe>--}}
{{--                                                <div class="player js-last-el">--}}
{{--                                                    <div class="img-player-scaler">--}}


{{--                                                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/LN_OJIsheyk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>--}}
{{--                                                        <div class="player-btn"><span class="icon-play"></span></div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}



                </div>
                {{--            <div class="game-toggle-zone">--}}
                {{--                <div class="sticky-bl">--}}
                {{--                    <div class="sticky-img-wrap"><img src="{{ asset('img/sticky-bl.jpg') }}" alt="sticky-bl"></div>--}}
                {{--                </div>--}}
                {{--            </div>--}}
            </div>
            <div class="game-toggle-bl" data-toggle="series">
                <div class="container">
                    @if(!empty($similarGames))
                        <div class="headline">
                            <h2 class="title">{{ $lang['games_from_this_series'] }}</h2>
                        </div>
                        <div class="thumbs">
                            {!! $similarGames !!}
                        </div>
                    @endif
                </div>
            </div>
            <div class="game-toggle-bl">
                <div class="container">
                    <div class="headline">
                        <h2 class="title">{{ $lang['similar_games'] }}</h2>
                    </div>
                    <div class="thumbs" id="boxSeries">
                        {!! $gamesSeries !!}
                    </div>
                    @if(isset($more_page))
                        <button style="@if(!$more_page) display: none; @endif " type="button" class="primary-btn loadmore" onclick="GamePage.loadGamesSeries({{ $more_page }})">{{ $lang['show_more'] }}</button>
                    @endif
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
                </div>
            </div>
            <div class="game-toggle-bl" data-toggle="feedback">
                <div class="container">
                    <div class="feed">
                        {{--load comments via ajax--}}
                        {!!  $comments->html ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="not-working">
        <button class="bg-closer" type="button">
            <span class="icon-close"></span>
        </button>
        <div class="not-working-inner">
            <h3 class="title">{{ $popup->name ?? '' }}</h3>
            {!! $popup->text ?? '' !!}
        </div>
    </div>
@endsection
@section('buttom_div')
    <div id="share" class="popup">
        <div class="popup__title">{{ $lang['share_game'] }} "{{ $game_lang->name }}"</div>
        <div data-mobile-view="false" data-share-size="30" data-like-text-enable="false" data-background-alpha="0.0"
             data-pid="1872409" data-mode="share" data-background-color="#ffffff" data-share-shape="round"
             data-share-counter-size="12" data-icon-color="#ffffff" data-mobile-sn-ids="fb.tw.wh.tm.vb."
             data-text-color="#000000" data-buttons-color="#ffffff" data-counter-background-color="#ffffff"
             data-share-counter-type="disable" data-orientation="horizontal" data-following-enable="false"
             data-sn-ids="fb.tw.wh.tm.vb." data-preview-mobile="false" data-selection-enable="false"
             data-exclude-show-more="true" data-share-style="6" data-counter-background-alpha="1.0"
             data-top-button="false" class="uptolike-buttons popup__share"></div>
        {{--<div class="popup__share">
            <i class="icon-viber"></i>
            <i class="icon-wa"></i>
            <i class="icon-vk1"><span class="path1"></span><span class="path2"></span></i>
            <i class="icon-tg"></i>
            <i class="icon-fb"><span class="path1"></span><span class="path2"></span></i>
            <i class="icon-twitter"><span class="path1"></span><span class="path2"></span></i>
            <i class="icon-ms"><span class="path1"></span><span class="path2"></span></i>
        </div>--}}
    </div>
    <div id="problem" class="popup">
        <div class="popup__title">{{ $popup->name ?? '' }}</div>
        <div class="popup__bigtext">
            {!! $popup->text ?? '' !!}
        </div>
    </div>
    <div id="editcomment" class="popup">
        <div class="popup__title">{{ $lang['your_feedback_game'] }} "{{ $game_lang->name }}"</div>
        <div class="popup__editcomment">
            <div class="comments__textarea">
                <div>
                    <textarea name="edit_comment" rows="6" class="hidden-xs hidden-sm"
                              placeholder="{{ $lang['tell_us_your_impression_game'] }}" required></textarea>
                </div>
                {{--<textarea name="comment" rows="6" class="visible-xs visible-sm comments__textareainput" placeholder="{{ $lang['tell_us_your_impression_game'] }}" required></textarea>--}}
            </div>
            <button class="button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> {{ $lang['processing'] }}"
                    onclick="gameComments.updateComment(this);" type="button">{{ mb_ucfirst($lang['change']) }}</button>
            <button class="button isFlat"
                    data-loading-text="<i class='fa fa-spinner fa-spin '></i> {{ $lang['processing'] }}"
                    onclick="gameComments.deleteComment(this);" type="button">{{ $lang['delete'] }}</button>
        </div>
    </div>
    <div class="share-pop">
        <button class="bg-closer" type="button"><span class="icon-close"></span></button>
        <div class="share-pop-title">{{$lang['share']}} "{{$game_lang->name}}"</div>
    </div>
    <script type="text/javascript">
        function uptolikeSite() {
            (function (w, doc) {
                if (!w.__utlWdgt) {
                    w.__utlWdgt = true;
                    var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
                    s.type = 'text/javascript';
                    s.charset = 'UTF-8';
                    s.async = true;
                    s.src = ('https:' == w.location.protocol ? 'https' : 'http') + '://w.uptolike.com/widgets/v1/uptolike.js';
                    var h = d[g]('body')[0];
                    h.appendChild(s);
                }
            })(window, document);
        }
    </script>

@endsection
@section('script')
    <script src="{{ asset('assets/js/timeme_js/timeme.min.js') }}"></script>

    <script type="application/javascript">
        var $genresAboutGame = $('#genresAboutGame');
        var $gamesSeries = $('#boxSeries');
        var $similarGames = $('.similarGames');

        var loadingP = '<div style="text-align: center" class="text-center lading-sector">\n' +
        '                    <img src="https://img.gamesgo.net/images/unnamed.gif">\n' +
        '                </div>';

        /*TimeMe.initialize({
            currentPageName: "game-{{ $game->url }}", // current page
            idleTimeoutInSeconds: 30 // seconds
        });

        window.onbeforeunload = function (event) {
            xmlhttp=new XMLHttpRequest();
            xmlhttp.open("{{ route('games.time', ['game' => $game->id]) }}", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var timeSpentOnPage = TimeMe.getTimeOnCurrentPageInSeconds();
            xmlhttp.send(timeSpentOnPage);
        };*/

        $("html").mouseenter(function () {
            $(this).addClass('mouseenter');
        }).mouseleave(function () {
            $(this).removeClass('mouseenter');
        });

        var GamePage = {
            init: function () {
                //GamePage.loadGenresAboutGame();
                //GamePage.loadGamesSeries(1);
                lozad('.demilazyload').observe();
                //GamePage.loadGamesSimilar();

                $('.game-frame-inner').on('click', function () {
                    GamePage.gamePlay();
                });
                $('#buttonShare').on('click', function () {
                    var flagShare = $('#buttonShare').attr('data-close');
                    if (flagShare == 'true') {
                        GamePage.closeShare();
                    } else {
                        GamePage.viewShare();
                    }
                })
            },
            shareMob: function () {
                if (navigator.share !== undefined) {
                    navigator.share({
                        title: '{{ $game_lang->seo_name ?? sprintf($lang['game_s_play_online'], $game_lang->name) }}',
                        url: '{{ route('seo.url', ['url' => $game->url]) }}'
                    });
                } else {
                    if ($('.wrapper').hasClass('has-open-share-pop')) {
                        $('.wrapper').removeClass('has-open-share-pop');
                    } else {
                        $('.wrapper').addClass('has-open-share-pop');
                    }
                }
            },
            gamePlay: function () {
                {{-- $.post( "{{ route('games.play', ['game' => $game->id]) }}"); --}}
                TimeMe.initialize({
                    currentPageName: "game-{{ $game->url }}", // current page
                    idleTimeoutInSeconds: 30 // seconds
                });

                setInterval(function () {
                    if ($("html").hasClass('mouseenter')) {
                        $.post("{{ route('games.time', ['game' => $game->id]) }}?time=" + TimeMe.getTimeOnCurrentPageInSeconds());
                    }
                }, 60000); // каждые 60 секунд

            },
            loadGenresAboutGame: function () {
                $.ajax({
                    type: "POST",
                    url: '{{ route('games.genre', ['game_id' => $game->id]) }}',
                    cache: false,
                    beforeSend: function () {
                        // setting a timeout
                        $genresAboutGame.html(loadingP);
                    },
                    success: function (data) {
                        if (data.success) {
                            $genresAboutGame.html(data.html);

                            lozad('.demilazyload').observe();
                        }
                    }
                });
            },
            loadGamesSimilar: function () {
                $.ajax({
                    type: "POST",
                    url: '{{ route('games.similar', ['game_id' => $game->id]) }}',
                    cache: false,
                    beforeSend: function () {
                        // setting a timeout
                        //$similarGames.html(loadingP);
                    },
                    success: function (data) {
                        if (data.success) {

                            $similarGames.html(data.html);
                            if (data.html != "") {
                                $('.tabSim').show();
                                // GamePage.itemsOwls('.similarGames');

                                lozad('.demilazyload').observe();

                            }

                        } else {
                            $('.similarDel').remove();
                        }
                    }
                });
            },
            loadGamesSeries: function (page) {
                $('.loadmore').remove();
                $.ajax({
                    type: "POST",
                    url: '{{ route('games.series', ['game_id' => $game->id]) }}',
                    data: {page: page},
                    cache: false,
                    beforeSend: function () {
                        // setting a timeout
                        // if (page == 1) {
                        //     $gamesSeries.html(loadingP);
                        // } else {

                        // }

                        $gamesSeries.append(loadingP);
                    },
                    success: function (data) {
                        if (data.success) {
                            $('.lading-sector').remove();
                            if (page == 1) {
                                $gamesSeries.html(data.html);
                            } else {
                                if ($gamesSeries.find('.load-more-series').length) {
                                    $gamesSeries.find('.load-more-series').remove();
                                }
                                $gamesSeries.append(data.html);
                                if(data.more_page){
                                    $('<button type="button" class="primary-btn loadmore" onclick="GamePage.loadGamesSeries('+data.more_page+')">{{ $lang['show_more'] }}</button>').insertAfter($gamesSeries);
                                }
                            }

                            if (data.html != "") {
                                //$('.tabSim').show();
                                // GamePage.itemsOwls('.gamesSeries');
                            }
                            showThumbPreview();
                            lozad('.demilazyload').observe();

                        } else {
                            $('#boxSeries').remove();
                        }
                    }
                });
            },
            // itemsOwls: function (thisDiv) {
            //     if ($(document).width() > 767) {
            //         var itemsOwls = $(thisDiv).find('.js-carousel-items');
            //         itemsOwls.on('changed.owl.carousel', function (event) {
            //             if (event.item.count - event.page.size < event.item.index + 1) {
            //                 $(event.target).addClass('isLast');
            //             } else {
            //                 $(event.target).removeClass('isLast');
            //             }
            //             if (!event.item.index) {
            //                 $(event.target).addClass('isFirst');
            //             } else {
            //                 $(event.target).removeClass('isFirst');
            //             }
            //         });
            //         itemsOwls.owlCarousel({
            //             loop: false,
            //             margin: 0,
            //             nav: true,
            //             dots: false,
            //             responsive: {
            //                 1199: {
            //                     items: 5
            //                 },
            //                 991: {
            //                     items: 4
            //                 },
            //                 768: {
            //                     items: 3
            //                 },
            //                 0: {
            //                     items: 2
            //                 }
            //             }
            //         });
            //     }
            //     itemWrapMouseenter();
            // },
            like: function (thisBtn, like) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('games.like', ['game' => $game->id]) }}',
                    cache: false,
                    data: {like: like},
                    beforeSend: function () {
                        // setting a timeout
                        $(thisBtn).addClass('isActive').parent().find('button').prop('disabled', true);
                    },
                    success: function (data) {
                        if (data.error) {
                            $(thisBtn).removeClass('isActive').parent().find('button').prop('disabled', false);
                        }

                        if (data.likes) {
                            if (data.likes.dislikes) {
                                $('.game-review-dislike').html(
                                    '<i class="icon-dislike"></i><span class="game-review-num">' +
                                    data.likes.dislikes + '</span>');
                            }
                            if (data.likes.likes) {
                                $('.game-review-like').html(
                                    '<i class="icon-like"></i><span class="game-review-num">' +
                                    data.likes.likes + '</span>');
                            }

                            if (data.likes.dislikes) {
                                $('.start-btn-dislike').html(
                                    '<i class="icon-dislike"></i><span class="start-btn-text">' +
                                    data.likes.dislikes + '</span>');
                            }

                            if (data.likes.likes) {
                                $('.start-btn-like').html(
                                    '<i class="icon-like"></i><span class="start-btn-text">' +
                                    data.likes.likes + '</span>');
                            }
                        }

                        showAlert(data.message, data.error == false);
                        // alert(data.message);

                        lozad('.demilazyload').observe();
                    }
                });
            },
            favorite: function (thisBtn) {

                $.ajax({
                    type: "POST",
                    url: '{{ route('games.favorite', ['game' => $game->id]) }}',
                    cache: false,
                    data: {favorite: !$(thisBtn).hasClass('isActive')},
                    beforeSend: function () {
                        // setting a timeout
                        $(thisBtn).prop('disabled', true);
                    },
                    success: function (data) {
                        if (data.success) {
                            if ($(thisBtn).hasClass('isActive')) {
                                $(thisBtn).removeClass('isActive').find('.game-btn-text').text('{{ $lang['to_favorites'] }}');
                            } else {
                                $(thisBtn).addClass('isActive').find('.game-btn-text').text('{{ $lang['in_favorites'] }}');
                            }
                        }

                        showAlert(data.message, data.success);
                        // alert(data.message);

                        lozad('.demilazyload').observe();
                    },
                    complete: function () {
                        $(thisBtn).prop('disabled', false);
                    }
                });
            },
            viewShare: function () {
                $('#buttonShare').attr('data-close', 'true');
                $('#boxShare').show();
            },
            closeShare: function () {
                $('#buttonShare').attr('data-close', 'false');
                $('#boxShare').hide();
            }
        };

        var $textBtnSddForm = $('.buttonAddComment').html();

        var gameComments = {

            currentPage: 2,
            full: true,
            changeCommentId: null,

            init: function () {
                this.divComments = $('.content-comments');
                this.loadmore = $('.loadmore');
                this.formComment = $('#addComment');
                this.textareaAdd = $('#textareaAdd');
                this.buttonAddComment = $('.buttonAddComment');
                this.playerReviewsTips = $('.playerReviewsTips');
                this.editComment = $('textarea[name="edit_comment"]');

                // gameComments.textareaAdd.emojioneArea({
                //     search: false,
                //     hidePickerOnBlur: true,
                //     //saveEmojisAs: true,
                //     events: {
                //         paste: function (editor, event) {
                //             gameComments.btnCommentActive();
                //         },
                //         keypress: function (editor, event) {
                //             gameComments.btnCommentActive();
                //         },
                //         keyup: function (editor, event) {
                //             gameComments.btnCommentActive();
                //         },
                //         keydown: function (editor, event) {
                //             gameComments.btnCommentActive();
                //         },
                //         change: function (editor, event) {
                //             gameComments.btnCommentActive();
                //         },
                //         emojibtn_click: function (button, event) {
                //             //$('.buttonAddComment').prop('disabled', false);
                //         }
                //     }
                // });

                //gameComments.load();
                gameComments.full = false;
                @if($comments->show_more)
                $('.loadmore').show('slow');
                @else
                if ($('.hasMorePagesComment').length) {
                    $('.hasMorePagesComment').removeClass('isActive').removeClass('hasMorePagesComment').addClass('noMorePagesComment');
                }
                @endif
            },
            btnCommentActive: function () {
                let textValue = $('#textareaAdd').val();
                //console.log(textValue.length > 10);
                //gameComments.buttonAddComment.prop('disabled', textValue.length > 2);

                gameComments.formComment.find('.emojionearea-editor').bind('input propertychange keypress paste', function () {
                    $('.buttonAddComment').prop('disabled', $(this).html().length < 1);
                });
            },
            loadPage: function (page) {
                gameComments.currentPage = page;
                gameComments.full = true;
                gameComments.load();
            },
            loadMore: function () {
                //gameComments.currentPage++;
                gameComments.full = false;
                gameComments.load();
            },
            load: function () {
                $.ajax({
                    type: "POST",
                    url: '{{ route('comments.get.game', ['game_id' => $game->id]) }}',
                    data: {page: gameComments.currentPage, full: gameComments.full},
                    cache: false,
                    beforeSend: function () {
                        // setting a timeout
                        //gameComments.loadmore.hide();
                        $('.section-comments-child').append($($loaderW).addClass('abs-loader').width(gameComments.divComments.width() - 20));
                        /*if (gameComments.full) {
                            $($loaderW).height($('.content-comments').height());
                            gameComments.divComments.html($loaderW);
                        } else {
                            $('.loadmore').hide();
                            $($loaderW).height($('.content-comments').height());
                            $('.loadmore').before($loaderW);
                        }*/

                    },
                    success: function (data) {
                        if (data.success) {
                            $('.lading-sector').remove();
                            if (data.total > 0) {
                                gameComments.playerReviewsTips.text('(' + data.total + ')');
                            } else {
                                gameComments.playerReviewsTips.text('');
                            }

                            if (gameComments.full) {
                                gameComments.divComments.html(data.html);
                            } else {
                                $($loaderW).remove();
                                $($loaderW).removeClass('abs-loader').width('auto');
                                $('.loadmore_comments').before($(data.html).closest('.comments'));
                                $('.loadmore_comments').html(data.loadmore_text);
                                if(data.show_more == false){
                                    $('.loadmore_comments').hide();
                                }
                                $('a[data-page="' + data.current_page + '"]').addClass('isActive');
                                if ($('.noMorePagesComment').length) {
                                    $('.noMorePagesComment').removeClass('noMorePagesComment').addClass('hasMorePagesComment').addClass('isActive');
                                }
                            }
                            gameComments.full = false;
                            gameComments.currentPage = parseInt(data.current_page) + 1;

                            if (data.show_more) {
                                $('.loadmore').show('slow');
                            } else {
                                if ($('.hasMorePagesComment').length) {
                                    $('.hasMorePagesComment').removeClass('isActive').removeClass('hasMorePagesComment').addClass('noMorePagesComment');
                                }
                            }

                            lozad('.demilazyload').observe();
                        }
                    }
                });
            },
            add: function () {
                $.ajax({
                    type: "POST",
                    url: '{{ route('comments.new', ['game' => $game->id]) }}',
                    cache: false,
                    data: {comment: gameComments.textareaAdd.val()},
                    beforeSend: function () {
                        // setting a timeout
                        $('.buttonAddComment').html($('.buttonAddComment').data('loading-text'));//.prop('disabled', true);
                    },
                    success: function (data) {
                        if (data.success) {
                            gameComments.textareaAdd.val('');
                            gameComments.textareaAdd.data("emojioneArea").setText('');
                            gameComments.currentPage = 1;
                            gameComments.full = true;
                            gameComments.load();
                        }

                        showAlert(data.message, data.success);

                        lozad('.demilazyload').observe();
                    },
                    complete: function () {
                        $('.buttonAddComment').html($textBtnSddForm);//.prop('disabled', false);
                    }
                });
            },
            rate: function (commentId, rate) {

                var thisCommentDiv = $('div[data-comment-id="' + commentId + '"]');

                $.ajax({
                    type: "POST",
                    url: '{{ route('comments.rate') }}',
                    cache: false,
                    data: {rate: rate, comment_id: commentId},
                    beforeSend: function () {
                        // setting a timeout
                        thisCommentDiv.find('.comments__rate--' + (rate == 1 ? 'up' : 'down')).prop('disabled', true).addClass('isActive');
                    },
                    success: function (data) {
                        if (data.error) {
                            thisCommentDiv.find('.js-rate-up').prop('disabled', false).removeClass('isActive');
                        }

                        if (data.rates) {
                            thisCommentDiv.find('.comments__rate--current').prop('disabled', true);
                            if (data.rates >= 0) {
                                thisCommentDiv.find('.comments__rate--current').removeClass('isRed').text(data.rates);
                            } else {
                                thisCommentDiv.find('.comments__rate--current').addClass('isRed').text(data.rates);
                            }
                        }

                        showAlert(data.message, data.error == false);

                        lozad('.demilazyload').observe();
                    },
                    complete: function () {
                        $('.buttonAddComment').html($textBtnSddForm);//.prop('disabled', false);
                    }
                });
            },
            change: function (commentId) {

                gameComments.changeCommentId = commentId;

                var thisCommentDiv = $('div[data-comment-id="' + commentId + '"]');

                gameComments.editComment.data("emojioneArea").setText($.trim(thisCommentDiv.find('.comments__comment--text').html().replace(/(<br ?\/?>)*/g, "")));

                $.fancybox.close();
                $.fancybox.open({
                    src: '#editcomment',
                    opts: {
                        buttons: ['close'],
                        touch: false,
                        baseClass: 'fancybox-popup'
                    }
                });
            },
            updateComment: function (thisBtn) {
                var textBtn = $(thisBtn).html();
                var commentText = gameComments.editComment.val();

                if (gameComments.changeCommentId) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('comments.comment') }}/update/' + gameComments.changeCommentId,
                        cache: false,
                        data: {comment: commentText},
                        beforeSend: function () {
                            // setting a timeout
                            $(thisBtn).html($(thisBtn).data('loading-text')).prop('disabled', true);
                        },
                        success: function (data) {
                            if (data.success) {
                                $.fancybox.close();
                                gameComments.editComment.data("emojioneArea").setText('');
                                gameComments.editComment.val('');
                                $('div[data-comment-id="' + gameComments.changeCommentId + '"]').find('.comments__comment--text').html(commentText);
                                gameComments.changeCommentId = null;
                                gameComments.load();
                            }

                            showAlert(data.message, data.success);

                            lozad('.demilazyload').observe();
                        },
                        complete: function () {
                            $(thisBtn).html(textBtn).prop('disabled', false);
                        }
                    });
                }
            },
            deleteComment: function (thisBtn) {

                var textBtn = $(thisBtn).html();

                if (gameComments.changeCommentId) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('comments.comment') }}/delete/' + gameComments.changeCommentId,
                        cache: false,
                        beforeSend: function () {
                            // setting a timeout
                            $(thisBtn).html($(thisBtn).data('loading-text')).prop('disabled', true);
                        },
                        success: function (data) {
                            if (data.success) {
                                $.fancybox.close();
                                gameComments.editComment.data("emojioneArea").setText('');
                                gameComments.editComment.val('');
                                gameComments.changeCommentId = null;

                                gameComments.currentPage--;
                                gameComments.full = true;
                                gameComments.load();
                            }

                            showAlert(data.message, data.success);

                            lozad('.demilazyload').observe();
                        },
                        complete: function () {
                            $(thisBtn).html(textBtn).prop('disabled', false);
                        }
                    });
                }
            }
        };

        $('#scrollToSames').on('click', function (e) {
            e.preventDefault();
            $([document.documentElement, document.body]).animate({
                scrollTop: $('#boxSeries').offset().top
            }, 500);
            return false;
        });

        $(function () {
            'use strict';
            //jQuery code here
            GamePage.init();
            gameComments.init();
        });

        fetch('https://extreme-ip-lookup.com/json/')
            .then(res => res.json())
            .then(response => {
                //console.log("Country: ", response.country);
                if (response.countryCode != "UA") {
                    $('div.uptolike-buttons').attr('data-mobile-sn-ids', 'fb.tw.wh.tm.vb.vk.ok.').attr('data-sn-ids', 'fb.tw.wh.tm.vb.vk.ok.');
                    // uptolikeSite();
                } else {
                    // uptolikeSite();
                }

            })
            .catch((data, status) => {
                // uptolikeSite();
            })


        document.addEventListener("DOMContentLoaded", sliderFunction);

        function sliderFunction() {
            var game_id = {{ $game->id }}
            $.ajax({
                type:'POST',
                url: '{{route('games.related')}}',
                data:{
                    "_token": "{{ csrf_token() }}",
                    "game_id": game_id,
                },
                datatype: 'JSON',
                success: function (response) {
                    $('.game-carousel').html(response);
                    initSliderRangeGames();
                    showThumbPreview();
                    lozad('.demilazyload').observe();
                }
            });
        }
    </script>

    @if($game->no_block_ad && !isMobileDevice())

        <script src="{{ asset('assets/js/fuckadblock.js') }}"></script>
        <script type="application/javascript">
            if (typeof fuckAdBlock !== 'undefined' || typeof FuckAdBlock !== 'undefined') {
                $('.adblocker-line').show().css({'opacity': '1', 'visibility': 'visible'});
            }
        </script>
    @endif
@endsection
