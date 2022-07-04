@forelse($games as $game)
<div class="category">
    <a href="{{ route('seo.url', ['slug' => $game->url]) }}" class="category-link">
        <div class="category-img-wrap">
            <img src="{{ $game->getUrlImage() }}"
                 alt="{{ $game->value }}"
                 srcset="/images/watermark_300x300.png 100w"
                 data-srcset="{{ $game->getUrlImage() }} 100w"
                 sizes="100vw"
                 class="demilazyload">
         </div>
        <div class="category-name">
            <div class="category-text">{{ $game->value }}</div>
        </div>
    </a>
</div>
@empty
    <p class="text-center search__title">{{ $lang['nothing_found'] }}</p>
@endforelse

@forelse($pages as $page)
<div class="category">
    <a href="{{ route('seo.url', ['slug' => $page->url]) }}" class="category-link">
        <div class="category-img-wrap">
            <img src="{{ $page->getGenreImageURL() }}"
                 alt="{{ $page->title }}"
                 srcset="/images/watermark_300x300.png 100w"
                 data-srcset="{{  $page->getGenreImageURL() }} 100w"
                 sizes="100vw"
                 class="demilazyload">
         </div>
        <div class="category-name">
            <div class="category-text">{{ $page->title }}</div>
        </div>
    </a>
</div>
@empty
@endforelse