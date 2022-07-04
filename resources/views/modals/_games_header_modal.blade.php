@foreach($games as $game)
    <div class="thumb">
        <div class="thumb-inner">
            <div class="thumb-link-wrap">
                <a class="thumb-link" href="{{ $game->getUrlByLang($lang->code) }}">
                    <div class="cursor-layer"></div>
                    <div class="img-scaler">
                        <div class="thumb-video-wrap">
                            <video class="thumb-video" muted="" loop="" data-src="@if(!empty($game->video)){{ $game->video }}@endif"
                                playsinline="">
                            </video>
                        </div>
                        <img @if (!$game->mobi && isMobileDevice()) style="filter: grayscale(100%);"
                            @endif class="img-scalable demilazyload"
                            src="{{ $game->getMainImage() }}"
                            srcset="/images/watermark_300x300.png 100w"
                            data-srcset="{{ $game->getImageByParams(\App\Options\ImageSizeOption::GAME_THUMB) }} 100w"
                            sizes="100vw"
                            alt="{{ $game->getTitle($current_locale->id) }}">
                        @if (!$game->mobi && isMobileDevice())
                            <div class="item__label">
                                <span><img style="height: 20px; width: 20px" src="{{ Storage::disk('app')->url('images/computer.svg') }}"/></span>
                            </div>
                        @endif
                        @if($game->isBest($category ?? null) || (!empty($best_game) && $game->best_game == 1))
                            <div class="label-popular"><i class="icon-fire"></i></div>
                        @endif
                        @if($game->isUserView())
                            <span class="label-played"><i class="icon-gamepad"></i></span>
                        @endif
                        @if($game->checkFavorite())
                            <span class="label-fav"><i class="icon-star"></i></span>
                        @endif
{{--                        @if($game->rating > 0)--}}
{{--                            <div class="label-progress">--}}
{{--                                <div class="label-progress-percents" data-percent="{{ $game->rating }}">--}}
{{--                                    <svg class="label-progress-svg">--}}
{{--                                        <use class="label-progress-inner" xlink:href="#percent" style="stroke-dasharray: 110px, 16px;">--}}
{{--                                            <circle id="percent" cx="50%" cy="50%" r="50%" stroke-alignment="inner"></circle>--}}
{{--                                        </use>--}}
{{--                                    </svg>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    </div>
                    <div class="thumb-desc">{{ $game->getTitle($lang->id) }}</div>
                </a>
            </div>
        </div>
    </div>
@endforeach