@extends('layouts.app')

@section('seo_title', $lang_page->seo_title ?? $lang_page->seo_name ?? $lang_page->h1 ?? $lang_page->name)
@section('seo_description', $lang_page->seo_description ?? $lang_page->description_top)

@section('content')
    <main class="page page_descr">

        <div class="b-breadcrumbs">
            <div class="b-breadcrumbs__container _container">
                @foreach($breadcrumbs as $breadcrumb)
                    <a class="b-breadcrumbs__item" href="{{$breadcrumb['url']}}">{{$breadcrumb['name']}}</a>
                @endforeach
            </div>
        </div>

        <div class="b-descr">
            <div class="b-descr__container _container">
                <div class="b-descr__content">
                    <div class="b-game">
                        <div class="b-game__content">
                            <div class="b-game__window">
                                <a class="b-game__window-back">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M15.8332 10H4.1665" stroke="#EA0042" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round" />
                                        <path d="M9.99984 15.8333L4.1665 9.99996L9.99984 4.16663" stroke="#EA0042" stroke-width="2"
                                              stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                                <div class="b-game__window-name">{{$lang_page->name}}</div>
                                @if(isMobileDevice() && !$game->mobi)
                                    <div class="b-game__window-onlypc">this mod is only for pc</div>
                                @endif
                                <div class="b-game__image">
                                    <picture>
                                        <source srcset="{{ $game->getMainImage() }}" media="(max-width: 899.98px)">
                                        <img src="{{ $game->getMainImage() }}" alt="{{$lang_page->name}}">
                                    </picture>
                                </div>
                                <a
                                        class="button button_orange b-game__start-btn"
                                        href=""
                                        alt="{{$lang_page->name}}"
                                        data-iframe-src="{{ $game->iframe_url }}"
                                        data-sandbox="{{ $game->sandbox }}"
                                        data-target-blank="{{ $game->target_blank ? 1 : 0 }}"
                                        data-fullscreen="true"
                                        data-horizontal="{{$game->horizontal}}"
                                        data-iframe-style="{{ $game->calculateIframeStyles() }}"
                                        data-wg-content="true"
                                >
                                    {{$lang['play']}}
                                </a>
                            </div>
                            <div class="b-game__actions">
                                <div class="b-game__actions-image">
                                    <picture><source srcset="{{ $game->getMainImage() }}"><img src="{{ $game->getMainImage() }}" style="max-height: 40px"></picture>
                                </div>
                                <div class="b-game__actions-name">{{$lang_page->name}}</div>
                                <a class="button" href="{{$game->link_game}}">{{$lang['download']}}</a>
                                <a class="b-game__actions-item b-game__actions-like _popup-link @if($game->checkUserLike()) active @endif" onclick="GamePage.like(this, 1);">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path
                                                d="M5.83366 18.3337H3.33366C2.89163 18.3337 2.46771 18.1581 2.15515 17.8455C1.84259 17.5329 1.66699 17.109 1.66699 16.667V10.8337C1.66699 10.3916 1.84259 9.96771 2.15515 9.65515C2.46771 9.34259 2.89163 9.16699 3.33366 9.16699H5.83366M11.667 7.50033V4.16699C11.667 3.50395 11.4036 2.86807 10.9348 2.39923C10.4659 1.93038 9.83003 1.66699 9.16699 1.66699L5.83366 9.16699V18.3337H15.2337C15.6356 18.3382 16.0256 18.1973 16.3319 17.937C16.6382 17.6767 16.8401 17.3144 16.9003 16.917L18.0503 9.41699C18.0866 9.17812 18.0705 8.93423 18.0031 8.7022C17.9357 8.47018 17.8187 8.25557 17.6602 8.07325C17.5017 7.89094 17.3054 7.74527 17.085 7.64634C16.8645 7.54741 16.6252 7.49759 16.3837 7.50033H11.667Z"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <span id="likes_count_num">@if($game->game_likes > 0){{ $game->game_likes }}@endif</span>
                                </a>
                                <a class="b-game__actions-item b-game__actions-dislike _popup-link @if($game->checkUserDislike()) active @endif" onclick="GamePage.like(this, 0);">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                                        <path
                                                d="M16 0.999873H18.67C19.236 0.989864 19.7859 1.18801 20.2154 1.55669C20.6449 1.92538 20.9241 2.43893 21 2.99987V9.99987C20.9241 10.5608 20.6449 11.0744 20.2154 11.4431C19.7859 11.8117 19.236 12.0099 18.67 11.9999H16M9 13.9999V17.9999C9 18.7955 9.31607 19.5586 9.87868 20.1212C10.4413 20.6838 11.2044 20.9999 12 20.9999L16 11.9999V0.999873H4.72C4.23767 0.99442 3.76962 1.16347 3.40209 1.47587C3.03457 1.78827 2.79232 2.22297 2.72 2.69987L1.34 11.6999C1.29649 11.9865 1.31583 12.2792 1.39666 12.5576C1.47749 12.8361 1.6179 13.0936 1.80814 13.3124C1.99839 13.5311 2.23392 13.7059 2.49843 13.8247C2.76294 13.9434 3.05009 14.0032 3.34 13.9999H9Z"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span id="dislikes_count_num" >@if($game->game_dislikes > 0){{ $game->game_dislikes }}@endif</span>
                                </a>
                                <div class="b-game__actions-item b-game__actions-share">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path
                                                d="M18 8C19.6569 8 21 6.65685 21 5C21 3.34315 19.6569 2 18 2C16.3431 2 15 3.34315 15 5C15 6.65685 16.3431 8 18 8Z"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                                d="M6 15C7.65685 15 9 13.6569 9 12C9 10.3431 7.65685 9 6 9C4.34315 9 3 10.3431 3 12C3 13.6569 4.34315 15 6 15Z"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                                d="M18 22C19.6569 22 21 20.6569 21 19C21 17.3431 19.6569 16 18 16C16.3431 16 15 17.3431 15 19C15 20.6569 16.3431 22 18 22Z"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8.58984 13.5098L15.4198 17.4898" stroke="#ffffff" stroke-width="2"
                                              stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M15.4098 6.50977L8.58984 10.4898" stroke="#ffffff" stroke-width="2"
                                              stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="b-game__actions-item b-game__actions-fullscreen">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path
                                                d="M6 1H3C2.46957 1 1.96086 1.21071 1.58579 1.58579C1.21071 1.96086 1 2.46957 1 3V6M19 6V3C19 2.46957 18.7893 1.96086 18.4142 1.58579C18.0391 1.21071 17.5304 1 17 1H14M14 19H17C17.5304 19 18.0391 18.7893 18.4142 18.4142C18.7893 18.0391 19 17.5304 19 17V14M1 14V17C1 17.5304 1.21071 18.0391 1.58579 18.4142C1.96086 18.7893 2.46957 19 3 19H6"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="b-game-info">
                        <div class="b-game-info__content tabs-block _tabs">
                            <nav class="b-game-info__nav tabs-block__nav">
                                <div class="b-game-info__nav-item b-game-info__nav-item_descr tabs-block__item _tabs-item _active">
                                    {{$lang['Description']}}</div>
                                <div class="b-game-info__nav-item b-game-info__nav-item_controls tabs-block__item _tabs-item">{{$lang['game_controls']}}</div>
                                <div class="b-game-info__nav-item b-game-info__nav-item_comments tabs-block__item _tabs-item">
                                    {{$lang['Comments']}} {!!  $game->publicComments->count() > 0 ? '(' . $game->publicComments->count() . ')' : '' !!}</div>
                                <div class="b-game-info__nav-item b-game-info__nav-item_credits tabs-block__item _tabs-item">{{$lang['Credits']}}
                                </div>
                            </nav>
                            <div class="b-game-info__body tabs-block__body">
                                <div
                                        class="b-game-info__block b-game-info__block_descr game-descr tabs-block__block _tabs-block _active">
                                    <div class="game-descr__title subtitle">{{$lang_page->name}}</div>
                                    {!! $lang_page->description !!}
                                </div>
                                <div class="b-game-info__block b-game-info__block_controls game-controls tabs-block__block _tabs-block">

                                    @if(is_array($comands) && count($comands) > 0)
                                        <div class="instruction-inner">
                                            @foreach($comands as $player => $comandsp)
                                                <div class="instruction-row">
                                                    @if(count($comands) > 1)
                                                        <div class="instruction-gamer">{{ $lang['player'] }} {{ $player }}</div>
                                                    @endif
                                                    <div class="instruction-col">
                                                        @foreach($comandsp as $comand)

                                                            <div class="game-controls__item">
                                                                @if(isset($comand['new-name']) && is_array($comand['new-name']) && !empty($comand['new-name'][0]))
                                                                    @if(isset($comand['new-name'][$current_locale->id]))
                                                                        <div class="game-controls__item-text text">
                                                                            {{ $comand['new-name'][$current_locale->id] }}
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    @if(!empty($comand['textarea']) && isset($comand['textarea'][$current_locale->id]) && $comand['textarea'][$current_locale->id] != "null")
                                                                        @if(isset($comand['textarea'][$current_locale->id]))
                                                                            <div class="game-controls__item-text text">
                                                                                {{ $comand['textarea'][$current_locale->id] }}
                                                                            </div>
                                                                        @endif
                                                                    @elseif(!empty($comand['input']))
                                                                        <div class="game-controls__item-text text">{{ \App\Models\ButtonsPlay::find($comand['input'][0])->getName($current_locale->id) }}</div>
                                                                    @endif
                                                                @endif
                                                                @if(!empty($comand['input']))
                                                                    @foreach($comand['input'] as $input)
                                                                        @if($input == 71)
                                                                            <div class="game-controls__item-text text">{{ $lang['or'] }}</div>
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
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="b-game-info__block b-game-info__block_comments game-comments tabs-block__block _tabs-block">

                                    <form class="game-comments__form form" action="javascript:void(0);">
                                        <div class="form__line game-comments__form-text">
                                            <textarea id="game-comment" class="input" name="form[]" data-value="{{ $lang['tell_us_your_impression_game'] }}"></textarea>
                                        </div>
                                        <div class="form__line game-comments__form-input">
                                            <input class="input _req" type="text" name="name" id="game-comment-name"  data-value="{{$lang['enter_your_name']}}"
                                                   data-error="This is required field">
                                        </div>
                                        <button class="form__btn button"  onclick="gameComments.add();" >{{ $lang['commenting'] }}</button>
                                    </form>

                                    <div class="game-comments__content">
                                        @foreach($game->publicComments as $comment)
                                            <div class="game-comments__item">
                                                <div class="game-comments__item-name">{{$comment->name}}</div>
                                                <div class="game-comments__item-date">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at)->setTimezone('Europe/Kiev')->format('Y-m-d') }}</div>
                                                <div class="game-comments__item-massage">{!! nl2br($comment->text) !!}</div>
                                                <div class="game-comments__item-info">
                                                    <div class="game-comments__item-info_like">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                            <path
                                                                    d="M5.83366 18.3337H3.33366C2.89163 18.3337 2.46771 18.1581 2.15515 17.8455C1.84259 17.5329 1.66699 17.109 1.66699 16.667V10.8337C1.66699 10.3916 1.84259 9.96771 2.15515 9.65515C2.46771 9.34259 2.89163 9.16699 3.33366 9.16699H5.83366M11.667 7.50033V4.16699C11.667 3.50395 11.4036 2.86807 10.9348 2.39923C10.4659 1.93038 9.83003 1.66699 9.16699 1.66699L5.83366 9.16699V18.3337H15.2337C15.6356 18.3382 16.0256 18.1973 16.3319 17.937C16.6382 17.6767 16.8401 17.3144 16.9003 16.917L18.0503 9.41699C18.0866 9.17812 18.0705 8.93423 18.0031 8.7022C17.9357 8.47018 17.8187 8.25557 17.6602 8.07325C17.5017 7.89094 17.3054 7.74527 17.085 7.64634C16.8645 7.54741 16.6252 7.49759 16.3837 7.50033H11.667Z"
                                                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <span>0</span>
                                                    </div>
                                                    <div class="game-comments__item-info_dislike">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                            <path
                                                                    d="M14.1662 1.66632H16.3912C16.8628 1.65798 17.3211 1.8231 17.679 2.13033C18.037 2.43757 18.2696 2.86553 18.3329 3.33298V9.16632C18.2696 9.63377 18.037 10.0617 17.679 10.369C17.3211 10.6762 16.8628 10.8413 16.3912 10.833H14.1662M8.33287 12.4997V15.833C8.33287 16.496 8.59626 17.1319 9.0651 17.6008C9.53394 18.0696 10.1698 18.333 10.8329 18.333L14.1662 10.833V1.66632H4.7662C4.36426 1.66177 3.97422 1.80265 3.66795 2.06298C3.36168 2.32331 3.15981 2.68556 3.09954 3.08298L1.94954 10.583C1.91328 10.8219 1.92939 11.0657 1.99675 11.2978C2.06412 11.5298 2.18112 11.7444 2.33965 11.9267C2.49819 12.109 2.69447 12.2547 2.91489 12.3536C3.13532 12.4526 3.37461 12.5024 3.6162 12.4997H8.33287Z"
                                                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="game-comments__button">
                                        <a class="button" href="#">More comments</a>
                                    </div>

                                </div>
                                <div class="b-game-info__block b-game-info__block_credits game-credits tabs-block__block _tabs-block">
                                    <div class="game-credits__item">
                                        <div class="game-credits__item-title">Mod Credit:</div>
                                        <div class="game-credits__item-line">
                                            <a class="game-credits__item-name" href="#">Burn-Out: </a>
                                            <span class="game-credits__item-text">Mod creator</span>
                                        </div>
                                        <div class="game-credits__item-line">
                                            <a class="game-credits__item-name" href="#">Shadow Mario: </a>
                                            <span class="game-credits__item-text"><b>FNF game engine.</b></span>
                                        </div>
                                    </div>
                                    <div class="game-credits__item">
                                        <div class="game-credits__item-title">Original FNF Credit:</div>
                                        <div class="game-credits__item-line">
                                            <a class="game-credits__item-name" href="#">ninja_muffin99</a>
                                            <span class="game-credits__item-text"><b> – Programming</b></span>
                                        </div>
                                        <div class="game-credits__item-line">
                                            <a class="game-credits__item-name" href="#">KadeDev</a>
                                            <span class="game-credits__item-text"> – Programming</span>
                                        </div>
                                        <div class="game-credits__item-line">
                                            <a class="game-credits__item-name" href="#">PhantomArcade3k and evilsk8r</a>
                                            <span class="game-credits__item-text"> – ARTISTS</span>
                                        </div>
                                        <div class="game-credits__item-line">
                                            <a class="game-credits__item-name" href="#">kawaisprite</a>
                                            <span class="game-credits__item-text"> – TASTY ASS MUSIC</span>
                                        </div>
                                        <div class="game-credits__item-line">
                                            <span class="game-credits__item-text">AND every person who contributed to Github source.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="b-baners">
                    <a class="b-baners__link" href="#">
                        <picture><source srcset="img/side.webp" type="image/webp"><img class="b-baners__image" src="img/side.jpg" alt="#"></picture>
                    </a>
                    <a class="b-baners__link" href="#">
                        <picture><source srcset="img/side.webp" type="image/webp"><img class="b-baners__image" src="img/side.jpg" alt="#"></picture>
                    </a>
                </div>
            </div>
        </div>

        <div id="new-mod" class="b-grid b-grid_active">
            <div class="b-grid__container _container">
                <h2 class="b-grid__title title text-center">New mods</h2>
                <div class="b-grid__content">
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/1.webp" type="image/webp"><img src="img/games/1.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF Sarvente’s Mid-Fight Masses</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/2.webp" type="image/webp"><img src="img/games/2.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF: Attack but Everyone take Turn Singing</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/3.webp" type="image/webp"><img src="img/games/3.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Accelerant Hank</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/4.webp" type="image/webp"><img src="img/games/4.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF QT Mod – Termination on Extreme difficulty!</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/5.webp" type="image/webp"><img src="img/games/5.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF [Full Week]</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/6.webp" type="image/webp"><img src="img/games/6.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Baldi’s Basics in Funkin</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/7.webp" type="image/webp"><img src="img/games/7.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Suicide Mouse [Sunday Night]</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/1.webp" type="image/webp"><img src="img/games/1.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF: Squid Game 1.5</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/2.webp" type="image/webp"><img src="img/games/2.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Impostor Among Us V3</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/3.webp" type="image/webp"><img src="img/games/3.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Impostor Among Us V3</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="b-grid b-grid_active">
            <div class="b-grid__container _container">
                <h2 class="b-grid__title title text-center">Popular mods</h2>
                <div class="b-grid__content">
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/1.webp" type="image/webp"><img src="img/games/1.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF Sarvente’s Mid-Fight Masses</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/2.webp" type="image/webp"><img src="img/games/2.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF: Attack but Everyone take Turn Singing</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/3.webp" type="image/webp"><img src="img/games/3.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Accelerant Hank</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/4.webp" type="image/webp"><img src="img/games/4.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF QT Mod – Termination on Extreme difficulty!</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/5.webp" type="image/webp"><img src="img/games/5.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF [Full Week]</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/6.webp" type="image/webp"><img src="img/games/6.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Baldi’s Basics in Funkin</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/7.webp" type="image/webp"><img src="img/games/7.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Suicide Mouse [Sunday Night]</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/1.webp" type="image/webp"><img src="img/games/1.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF: Squid Game 1.5</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/2.webp" type="image/webp"><img src="img/games/2.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Impostor Among Us V3</span>
                    </a>
                    <a class="b-grid__item" href="#">
              <span class="b-grid__item-image">
                <picture><source srcset="img/games/3.webp" type="image/webp"><img src="img/games/3.jpg" alt="#"></picture>
                <span class="b-grid__video">
                  <video muted autoplay loop>
                    <source src="https://gamesgo.net/storage/images/games/jewel-shuffle/jewel-shufflemp4_v.mp4"
                            type="video/mp4">
                  </video>
                </span>
              </span>
                        <span class="b-grid__item-name">FNF vs Impostor Among Us V3</span>
                    </a>
                </div>
                <a class="button" href="#">Show more</a>
            </div>
        </div>

        <div class="message message_done">
            Your vote is accepted!
            <div class="close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>
        <div class="message message_error">
            The interval beetwen the publication of comment is at least 5 minutes!
            <div class="close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

    </main>
@endsection

@section('script')
    <script type="application/javascript">
        console.log(123);
        var GamePage = {
            init: function () {

            },
            like: function (thisBtn, like) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('games.like', ['game' => $game->id]) }}',
                    cache: false,
                    data: {like: like},
                    beforeSend: function () {
                        // setting a timeout
                        // $(thisBtn).addClass('isActive').parent().find('button').prop('disabled', true);
                    },
                    success: function (data) {
                        if (data.error) {
                            // $(thisBtn).removeClass('isActive').parent().find('button').prop('disabled', false);
                        }

                        if (data.likes) {
                            if (data.likes.likes) {
                                $('#likes_count_num').html(data.likes.likes);
                            }

                            if (data.likes.dislikes) {
                                $('#dislikes_count_num').html(data.likes.dislikes);
                            }
                        }
                    }
                });
            },
        };

        var gameComments = {
            init: function () {
            },
            add: function () {
                $.ajax({
                    type: "POST",
                    url: '{{ route('comments.new', ['game' => $game->id]) }}',
                    cache: false,
                    data: {comment: $('#game-comment').val(), name: $('#game-comment-name').val()},
                    success: function () {
                        console.log(123);
                    },
                });
            },
        };

        // $(function () {
        //     'use strict';
        //     //jQuery code here
        //     GamePage.init();
        // });
    </script>
@endsection
