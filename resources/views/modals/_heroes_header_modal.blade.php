@foreach($heroes as $hero)
    <div class="category">
        <a class="category-link" href="{{$hero->getUrlByLang($lang->code)}}">
            <div class="category-img-wrap">
                <img class="demilazyload"
                     src="{{ $hero->getUrlImage() }}"
                     srcset="/images/watermark_300x300.png 100w"
                     data-srcset="{{ $hero->getUrlImage() }} 100w"
                     sizes="100vw"
                     alt="{{ $hero->name }}"
                >
            </div>
            <div class="category-name">
                <div class="category-text">{{ $hero->name }}</div>
            </div>
        </a>
    </div>
@endforeach