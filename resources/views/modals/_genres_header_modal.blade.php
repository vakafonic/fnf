@foreach($genres as $genry)
    <div class="category">
        <a class="category-link" href="{{ $genry->getUrlByLang($lang->code) }}">
            <div class="category-img-wrap">
                <img src="{{ $genry->getUrlImage() }}"
                     srcset="/images/watermark_300x300.png 100w"
                     data-srcset="{{ $genry->getImageByParams(\App\Options\ImageSizeOption::CATEGORY_THUMB) }} 100w"
                     sizes="100vw"
                     alt="{{ $genry->name ?? $genry->value }}"
                     class="demilazyload">
            </div>
            <div class="category-name">
                <div class="category-text">{{ $genry->name ?? $genry->value }}</div>
            </div>
        </a>
    </div>
@endforeach