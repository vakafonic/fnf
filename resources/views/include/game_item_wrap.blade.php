<a class="b-grid__item" href="{{ route('game', ['url' => $game->url]) }}">
    <span class="b-grid__item-image">
        <picture>
            <source srcset="{{ $game->video }}" type="image/mp4">
            <img alt="{{ $game->getTitle($current_locale->id) }}" src="{{ $game->getMainImage() }}">
        </picture>
        <span class="b-grid__video">
            <video autoplay loop muted>
                <source src="{{ $game->video }}" type="video/mp4">
            </video>
        </span>
    </span>
    <span class="b-grid__item-name">{{ $game->getTitle($current_locale->id) }}</span>
</a>
