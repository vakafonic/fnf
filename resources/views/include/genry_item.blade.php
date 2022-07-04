<div class="category">
    <a class="category-link" href="{{ route('seo.url', ['slug' => $genry->url]) }}">
        <div class="category-img-wrap">
            <img class="demilazyload"
                 src="{{ $genry->getUrlImage() }}"
                 srcset="/images/watermark_300x300.png 100w"
                 data-srcset="{{ $genry->getUrlImage() }} 100w"
                 alt="{{ $genry->name ?? $genry->value }}"
                 sizes="100vw">
        </div>
        <div class="category-name">
            <div class="category-text">{{ $genry->name ?? $genry->value }}</div>
        </div>
    </a>
</div>