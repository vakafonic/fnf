@if(!empty($buttom))
    <button class="item__delete" onclick="mainSite.deleteMyGame({{ $game->id }}, '{{ $buttom }}')" type="button"><span class="icon-close"></span></button>
@endif
<div class="item__wrap">
    <a href="{{ route('seo.url', ['slug' => $game->url]) }}" class="item__link"></a>
    <div class="item__img">
        {{--@if($game->getIsNew())
            <div class="item__label">
                New
            </div>
        @endif--}}
        {{--<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>--}}
        @if($game->checkFavorite())
            <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
        @endif
        @if($game->isBest($category ?? null) || (!empty($best_game) && $game->best_game == 1))
            <div class="item__label">
                <span class="icon-fire"></span>
            </div>
        @endif
        @if($game->rating > 0)
            <div class="item__percent">
                <div class="percent-circle js-circle" data-percent="{{ $game->rating }}">
                    <svg>
                        <use class="percent-circle-inner"
                             xlink:href="#percent-circle-svg"></use>
                    </svg>
                </div>
            </div>
        @endif
        {{--<button class="item__play" type="button">Играть</button>--}}
        <div class="item__preview">
            <video muted loop data-src="{{ $game->video }}" playsinline>
                <!-- MP4 must be first for iPad! -->
                @if(!empty($game->video))
                    {{--<source src="{{ $game->video }}" type="video/mp4">--}}
                @endif
                {{--<source src="https://clips.vorwaerts-gmbh.de/VfE.webm"
                        type="video/webm">
                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv"
                        type="video/ogg">--}}
            </video>
        </div>
        <img class="menu_lozad"
             sizes="100vw"
             src="{{ $game->getImage() }}"
             data-srcset="{{ $game->getImage() }} 100w"
             srcset="/images/watermark_300x300.png 100w"
             alt="{{ $game->getTitle($current_locale->id) }}">
    </div>
    <div class="item__name">{{ $game->getTitle($current_locale->id) }}</div>
</div>
